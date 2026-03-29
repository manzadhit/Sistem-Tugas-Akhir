<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeAkademikRequest;
use App\Http\Requests\Admin\UpdatePeriodeAkademikRequest;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodeAkademikController extends Controller
{
    public function index(Request $request)
    {
        $search   = trim((string) $request->query('search'));
        $semester = trim((string) $request->query('semester'));

        $periodes = PeriodeAkademik::query()
            ->when($search !== '', fn($q) => $q->where('tahun_ajaran', 'like', "%{$search}%"))
            ->when(in_array($semester, ['ganjil', 'genap'], true), fn($q) => $q->where('semester', $semester))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $periodeAktif = PeriodeAkademik::query()
            ->aktif()
            ->latest('mulai_at')
            ->first();

        return view('admin.periode-akademik.list-periode-akademik', compact('periodes', 'periodeAktif'));
    }

    public function create()
    {
        return view('admin.periode-akademik.create-periode-akademik');
    }

    public function store(StorePeriodeAkademikRequest $request)
    {
        $payload           = $request->validated();
        $langsungAktifkan  = $request->boolean('langsung_aktifkan');

        if ($langsungAktifkan) {
            // Buat periode baru dan langsung aktifkan dalam satu transaksi
            DB::transaction(function () use ($payload) {
                // Periode yang sedang aktif otomatis diselesaikan
                PeriodeAkademik::aktif()->update([
                    'status'     => 'selesai',
                    'selesai_at' => Carbon::today(),
                ]);

                PeriodeAkademik::create(array_merge($payload, ['status' => 'aktif']));
            });

            return redirect()->route('admin.periode.index')
                ->with('success', 'Periode akademik berhasil ditambahkan dan langsung diaktifkan.');
        }

        // Default: simpan sebagai draft
        PeriodeAkademik::create(array_merge($payload, ['status' => 'draft']));

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode akademik berhasil ditambahkan sebagai draft.');
    }

    public function edit(PeriodeAkademik $periodeAkademik)
    {
        return view('admin.periode-akademik.edit-periode-akademik', [
            'periode' => $periodeAkademik,
        ]);
    }

    public function update(UpdatePeriodeAkademikRequest $request, PeriodeAkademik $periodeAkademik)
    {
        // Jika bukan draft, hanya izinkan perubahan tanggal
        $data = $periodeAkademik->isDraft()
            ? $request->validated()
            : $request->only(['mulai_at', 'selesai_at']);

        $periodeAkademik->update($data);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode akademik berhasil diperbarui.');
    }

    public function destroy(PeriodeAkademik $periodeAkademik)
    {
        if (! $periodeAkademik->isDraft()) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Hanya periode berstatus draft yang dapat dihapus.');
        }

        $periodeAkademik->delete();

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode akademik berhasil dihapus.');
    }

    public function activate(PeriodeAkademik $periodeAkademik)
    {
        if (! $periodeAkademik->isDraft()) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Hanya periode berstatus draft yang dapat diaktifkan.');
        }

        DB::transaction(function () use ($periodeAkademik) {
            // Periode aktif sebelumnya otomatis diselesaikan
            PeriodeAkademik::aktif()->update([
                'status'     => 'selesai',
                'selesai_at' => Carbon::today(),
            ]);

            $periodeAkademik->update(['status' => 'aktif']);
        });

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode akademik {$periodeAkademik->tahun_ajaran} semester {$periodeAkademik->semester} berhasil diaktifkan.");
    }

    public function complete(PeriodeAkademik $periodeAkademik)
    {
        if (! $periodeAkademik->isAktif()) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Hanya periode berstatus aktif yang dapat diselesaikan.');
        }

        $periodeAkademik->update([
            'status'     => 'selesai',
            'selesai_at' => $periodeAkademik->selesai_at ?? Carbon::today(),
        ]);

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode akademik {$periodeAkademik->tahun_ajaran} semester {$periodeAkademik->semester} berhasil diselesaikan.");
    }
}
