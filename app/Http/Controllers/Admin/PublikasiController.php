<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PublikasiImport;
use App\Models\ProfileDosen;
use App\Models\PublikasiDosen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $kategori = $request->get('kategori');
        $tahun    = $request->get('tahun');
        $dosenId  = $request->get('dosen_id');

        $daftarPublikasi = PublikasiDosen::with('dosen')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhereHas('dosen', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
                });
            })
            ->when($kategori, fn($q) => $q->where('jenis_publikasi', $kategori))
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'  => PublikasiDosen::count(),
            'jurnal' => PublikasiDosen::where('jenis_publikasi', 'jurnal')->count(),
            'buku'   => PublikasiDosen::where('jenis_publikasi', 'buku')->count(),
            'haki'   => PublikasiDosen::where('jenis_publikasi', 'haki')->count(),
        ];

        $daftarDosen = ProfileDosen::orderBy('nama_lengkap')->get();

        return view('admin.publikasi.list-publikasi', compact('daftarPublikasi', 'stats', 'daftarDosen'));
    }

    public function create()
    {
        $daftarDosen = ProfileDosen::orderBy('nama_lengkap')->get();

        return view('admin.publikasi.create-publikasi', compact('daftarDosen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dosen_id'        => 'required|exists:profile_dosen,id',
            'judul'           => 'required|string|max:500',
            'jenis_publikasi' => 'required|in:jurnal,haki,buku',
            'tahun'           => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'penerbit'        => 'nullable|string|max:255',
            'url'             => 'nullable|url:http,https|max:500',
        ]);

        PublikasiDosen::create($validated);

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $publikasi = PublikasiDosen::with('dosen')->findOrFail($id);

        return view('admin.publikasi.detail-publikasi', compact('publikasi'));
    }

    public function edit($id)
    {
        $publikasi   = PublikasiDosen::findOrFail($id);
        $daftarDosen = ProfileDosen::orderBy('nama_lengkap')->get();

        return view('admin.publikasi.edit-publikasi', compact('publikasi', 'daftarDosen'));
    }

    public function update(Request $request, $id)
    {
        $publikasi = PublikasiDosen::findOrFail($id);

        $validated = $request->validate([
            'dosen_id'        => 'required|exists:profile_dosen,id',
            'judul'           => 'required|string|max:500',
            'jenis_publikasi' => 'required|in:jurnal,haki,buku',
            'tahun'           => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'penerbit'        => 'nullable|string|max:255',
            'url'             => 'nullable|url:http,https|max:500',
        ]);

        $publikasi->update($validated);

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $publikasi = PublikasiDosen::findOrFail($id);
        $publikasi->delete();

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:profile_dosen,id',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(
                new PublikasiImport((int) $validated['dosen_id']),
                $validated['file']
            );

            return back()->with('success', 'Import data publikasi berhasil.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
