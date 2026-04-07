<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kajur\VerifyLaporanRequest;
use App\Models\KajurSubmission;
use App\Notifications\KajurSubmissionReviewed;
use App\Services\Kajur\PenetapanPengujiService;
use Illuminate\Http\Request;

class PersetujuanKajurController extends Controller
{
    public function __construct(protected PenetapanPengujiService $penetapanPengujiService) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $persetujuan = KajurSubmission::with('tugasAkhir.mahasiswa')
            ->whereIn('tahapan', ['hasil', 'skripsi'])
            ->where('status', 'pending')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('tugasAkhir.mahasiswa', function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kajur.persetujuan-kajur', compact('persetujuan', 'search'));
    }

    public function show(KajurSubmission $persetujuan)
    {
        $persetujuan->load([
            'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
            'kajurSubmissionFiles' => fn($q) => $q->latest(),
        ]);

        return view('kajur.detail-persetujuan-kajur', compact('persetujuan'));
    }

    public function verify(VerifyLaporanRequest $request, KajurSubmission $persetujuan)
    {
        try {
            $reviewed = $this->penetapanPengujiService->verifyLaporan(
                kajurSubmission: $persetujuan,
                payload: $request->validated(),
                files: $request->file('files', [])
            );

            $reviewed->loadMissing('tugasAkhir.mahasiswa.user');
            $reviewed->tugasAkhir->mahasiswa?->user?->notify(new KajurSubmissionReviewed($reviewed));

            return back()->with('show_result_modal', $request->input('status'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menyimpan verifikasi.');
        }
    }
}
