<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPenguji;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;

class InputNilaiController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = $request->user()->profileDosen->id;

        $semua = DosenPenguji::with(['mahasiswa.tugasAkhir.ujian.jadwalUjian'])
            ->where('dosen_id', $dosenId)
            ->whereHas('mahasiswa.tugasAkhir.ujian', fn($q) => $q->where('jenis_ujian', 'skripsi'))
            ->get();

        $sudahDinilai = $semua->whereNotNull('nilai')->count();
        $menunggu     = $semua->whereNull('nilai')->count();

        $pengujiList = $semua
            ->when($request->filled('search'), function ($col) use ($request) {
                $search = strtolower($request->search);
                return $col->filter(
                    fn($p) => str_contains(strtolower($p->mahasiswa->nama_lengkap), $search)
                           || str_contains($p->mahasiswa->nim, $request->search)
                );
            })
            ->when($request->filled('peran'), fn($col) => $col->where('jenis_penguji', $request->peran))
            ->values()
            ->each(function ($penguji) {
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
        }

        return redirect()->route('dosen.nilai.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
