<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kajur\TetapkanPengujiRequest;
use App\Http\Requests\Kajur\VerifyLaporanRequest;
use App\Models\KajurSubmission;
use App\Models\ProfileDosen;
use App\Services\Kajur\PenetapanPengujiService;
use Illuminate\Http\Request;

class PengujiController extends Controller
{
    public function __construct(protected PenetapanPengujiService $penetapanPengujiService) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $permintaanPenguji = KajurSubmission::with('tugasAkhir.mahasiswa.dosenPembimbing.dosen')
            ->whereIn('status', ['pending', 'acc'])
            ->whereDoesntHave('tugasAkhir.mahasiswa.dosenPenguji')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('tugasAkhir.mahasiswa', function ($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate(8)
            ->withQueryString();

        return view('kajur.permintaan-penguji', compact('permintaanPenguji', 'search'));
    }

    public function show(KajurSubmission $permintaan)
    {
        $permintaan->load(['tugasAkhir.mahasiswa.dosenPembimbing.dosen', 'kajurSubmissionFiles' => fn($q) => $q->where('uploaded_by', 'mahasiswa')->latest()]);

        $mahasiswa = $permintaan->tugasAkhir->mahasiswa;
        $pembimbing = $mahasiswa->dosenPembimbing->pluck('id');
        $hasPenguji = $mahasiswa->dosenPenguji()->exists();

        $dosenPenguji = ProfileDosen::whereNotIn('id', $pembimbing)->limit(3)->get();

        return view('kajur.penetapan-penguji', compact('permintaan', 'dosenPenguji', 'hasPenguji'));
    }

    public function verifyLaporan(VerifyLaporanRequest $request, KajurSubmission $permintaan)
    {
        try {
            $this->penetapanPengujiService->verifyLaporan(kajurSubmission: $permintaan, payload: $request->validated(), files: $request->file('files', []));

            return back()->with('success', $request->input('status') . ' telah diberikan');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menyimpan verifikasi');
        }
    }

    public function tetapkanPenguji(KajurSubmission $permintaan, TetapkanPengujiRequest $request)
    {
        $dosenIds = $request->validated()['penguji_ids'];

        $mahasiswaId = $permintaan->tugasAkhir->mahasiswa->id;

        try {
            $this->penetapanPengujiService->tetapkanPenguji($mahasiswaId, $dosenIds);

            return back()->with('success', 'Penguji berhasil ditetapkan');
        } catch (\Throwable $th) {
            return back()->with('error', 'Penguji gagal ditetapkan');
        }
    }
}
