<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MataKuliahImport;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $mataKuliahs = MataKuliah::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            })
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mata-kuliah.list-mata-kuliah', compact('mataKuliahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ]);
        $mataKuliah = MataKuliah::create($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', "Mata kuliah {$mataKuliah->kode} - {$mataKuliah->nama} berhasil ditambahkan.");
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliah', 'kode')->ignore($mataKuliah->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $mataKuliah->update($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', "Mata kuliah {$mataKuliah->kode} - {$mataKuliah->nama} berhasil diperbarui.");
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->loadCount(['dosen', 'permintaanPembimbing']);

        if ($mataKuliah->dosen_count > 0 || $mataKuliah->permintaan_pembimbing_count > 0) {
            return redirect()->route('admin.mata-kuliah.index')
                ->with('error', "Mata kuliah {$mataKuliah->kode} - {$mataKuliah->nama} tidak dapat dihapus karena masih digunakan pada relasi dosen atau permintaan pembimbing.");
        }

        $namaMataKuliah = "{$mataKuliah->kode} - {$mataKuliah->nama}";

        $mataKuliah->delete();

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', "Mata kuliah {$namaMataKuliah} berhasil dihapus.");
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new MataKuliahImport, $validated['file']);

            return back()->with('success', 'Import berhasil');
        } catch (\Throwable $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
