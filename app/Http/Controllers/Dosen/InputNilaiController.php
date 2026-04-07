<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPenguji;
use App\Models\TugasAkhir;
use App\Models\Ujian;
use Illuminate\Http\Request;

class InputNilaiController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = $request->user()->profileDosen->id;

        $baseQuery = DosenPenguji::query()
            ->where('dosen_id', $dosenId)
            ->whereHas('mahasiswa.tugasAkhir.ujian', fn($q) => $q->where('jenis_ujian', 'skripsi'));

        $sudahDinilai = (clone $baseQuery)->whereNotNull('nilai')->count();
        $menunggu     = (clone $baseQuery)->whereNull('nilai')->count();

        $pengujiList = (clone $baseQuery)
            ->with(['mahasiswa.tugasAkhir.ujian.jadwalUjian'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('mahasiswa', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('peran'), fn($query) => $query->where('jenis_penguji', $request->peran))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pengujiList->getCollection()->each(function ($penguji) {
            $ujian = $penguji->mahasiswa->tugasAkhir?->ujian->firstWhere('jenis_ujian', 'skripsi');
            $penguji->jadwal = $ujian?->jadwalUjian;
        });

        return view('dosen.input-nilai', compact('pengujiList', 'sudahDinilai', 'menunggu'));
    }

    public function store(Request $request, DosenPenguji $dosenPenguji)
    {
        $request->validate([
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $this->authorize('inputNilai', $dosenPenguji);

        $dosenPenguji->update(['nilai' => $request->nilai]);

        $mahasiswaId = $dosenPenguji->mahasiswa_id;
        $semuaPenguji = DosenPenguji::where('mahasiswa_id', $mahasiswaId)
            ->get();

        $semuaSudahInput = $semuaPenguji->every(fn($p) => $p->nilai !== null);

        if ($semuaSudahInput && $semuaPenguji->count() === 3) {
            $bobot = ['penguji_1' => 50, 'penguji_2' => 30, 'penguji_3' => 20];

            $nilaiAkhir = $semuaPenguji->sum(
                fn($p) => $p->nilai * $bobot[$p->jenis_penguji] / 100
            );

            TugasAkhir::where('mahasiswa_id', $mahasiswaId)
                ->update(['nilai' => round($nilaiAkhir, 2)]);

            // Ubah status ujian skripsi → menunggu_hasil agar mahasiswa bisa upload hasil
            Ujian::whereHas('tugasAkhir', fn($q) => $q->where('mahasiswa_id', $mahasiswaId))
                ->where('jenis_ujian', 'skripsi')
                ->where('status', 'menunggu_nilai')
                ->update(['status' => 'menunggu_hasil']);
        }

        return redirect()->route('dosen.nilai.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
