<?php

namespace App\Http\Controllers\Kajur;

use App\Notifications\NewPembimbingRequest;
use App\Notifications\PembimbingAssigned;
use App\Notifications\PembimbingRequestReviewed;
use App\Models\ProfileDosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\PermintaanPembimbing;
use App\Models\TugasAkhir;

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $permintaanPembimbing = PermintaanPembimbing::with('mahasiswa')
            ->where('status', 'pending')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('kajur.permintaan-pembimbing', compact('permintaanPembimbing', 'search'));
    }

    public function show($permintaan)
    {
        $permintaan = PermintaanPembimbing::with('mahasiswa')->findOrFail($permintaan);

        request()->user()->unreadNotifications()
            ->where('type', NewPembimbingRequest::class)
            ->where('data->permintaan_pembimbing_id', $permintaan->id)
            ->update(['read_at' => now()]);

        $dosen = ProfileDosen::limit(2)->get();

        return view('kajur.penetapan-pembimbing', compact('permintaan', 'dosen'));
    }

    public function verifyBukti(Request $request, $permintaan)
    {
        $permintaan = PermintaanPembimbing::with('mahasiswa.user')->findOrFail($permintaan);

        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'required_if:status,ditolak|nullable|string|max:500'
        ]);

        $permintaan->status_verifikasi_bukti = $data['status'];
        $permintaan->catatan = $data['status'] == 'ditolak' ? $data['alasan'] : null;
        $permintaan->diproses_pada = now();

        $permintaan->save();

        $permintaan->mahasiswa?->user?->notify(new PembimbingRequestReviewed($permintaan));

        return back()->with('success', 'Verifikasi bukti berhasil disimpan.');
    }

    public function tetapkanPembimbing(Request $request, PermintaanPembimbing $permintaan)
    {
        $request->validate([
            'dosen_ids' => ['required', 'array', 'min:1'],
            'dosen_ids.*' => ['integer', 'exists:profile_dosen,id'],
        ]);

        $mahasiswaId = $permintaan->mahasiswa_id;
        $dosenIds = $request->input('dosen_ids');

        foreach ($dosenIds as $index => $dosenId) {
            DosenPembimbing::create(
                [
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $mahasiswaId,
                    'jenis_pembimbing' => 'pembimbing_' . $index + 1,
                    'tanggal_mulai' => now()
                ]
            );
        }

        $permintaan->update([
            'status' => 'disetujui',
            'diproses_pada' => now(),
        ]);

        $permintaan->save();

        TugasAkhir::create([
            'judul' => $permintaan->judul_ta,
            'mahasiswa_id' => $permintaan->mahasiswa_id,
        ]);

        $permintaan->loadMissing('mahasiswa.user');

        $permintaan->mahasiswa?->user?->notify(new PembimbingAssigned($permintaan));

        return back()->with('success', 'Pembimbing berhasil ditetapkan');
    }
}
