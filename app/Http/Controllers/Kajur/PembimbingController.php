<?php

namespace App\Http\Controllers\Kajur;

use App\Models\ProfileDosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\PermintaanPembimbing;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Storage;

class PembimbingController extends Controller
{
    public function index()
    {
        $permintaanPembimbing = PermintaanPembimbing::with('mahasiswa')->where('status', 'pending')->get();

        return view('kajur.permintaan-pembimbing', compact('permintaanPembimbing'));
    }

    public function show($permintaan)
    {
        $permintaan = PermintaanPembimbing::with('mahasiswa')->findOrFail($permintaan);

        $dosen = ProfileDosen::limit(2)->get();

        return view('kajur.penetapan-pembimbing', compact('permintaan', 'dosen'));
    }

    public function downloadBukti($permintaan)
    {
        $permintaan = PermintaanPembimbing::findOrFail($permintaan);

        $buktiPath = $permintaan->bukti_acc_path;

        if (!Storage::disk('public')->exists($buktiPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($buktiPath)
        );
    }

    public function showBukti($permintaan)
    {
        $permintaan = PermintaanPembimbing::findOrFail($permintaan);

        $buktiPath = $permintaan->bukti_acc_path;

        if (!Storage::disk('public')->exists($buktiPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file(
            Storage::disk('public')->path($buktiPath)
        );
    }

    public function verifyBukti(Request $request, $permintaan)
    {
        $permintaan = PermintaanPembimbing::findOrFail($permintaan);

        $buktiPath = $permintaan->bukti_acc_path;

        if (!Storage::disk('public')->exists($buktiPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'required_if:status,ditolak|nullable|string|max:500'
        ]);

        $permintaan->status_verifikasi_bukti = $data['status'];
        $permintaan->catatan = $data['status'] == 'ditolak' ? $data['alasan'] : null;
        $permintaan->diproses_pada = now();

        $permintaan->save();

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

        foreach($dosenIds as $index => $dosenId) {
            DosenPembimbing::create(
                [
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $mahasiswaId,
                    'jenis_pembimbing' => 'pembimbing_'.$index + 1,
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

        return back()->with('success', 'Pembimbing berhasil ditetapkan');
    }
}
