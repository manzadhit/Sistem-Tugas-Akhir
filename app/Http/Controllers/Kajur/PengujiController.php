<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kajur\VerifyLaporanRequest;
use App\Models\KajurSubmission;
use App\Services\Kajur\PenetapanPengujiService;

class PengujiController extends Controller
{
    public function __construct(protected PenetapanPengujiService $penetapanPengujiService) {}

    public function index()
    {
        $permintaanPenguji = KajurSubmission::with('tugasAkhir.mahasiswa.dosenPembimbing.dosen')->oldest()->get();

        return view('kajur.permintaan-penguji', compact('permintaanPenguji'));
    }

    public function show(KajurSubmission $permintaan)
    {
        $permintaan->load(['tugasAkhir.mahasiswa.dosenPembimbing.dosen', 'kajurSubmissionFiles' => fn($q) => $q->where('uploaded_by', 'mahasiswa')->latest()]);
        return view('kajur.penetapan-penguji', compact('permintaan'));
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
}
