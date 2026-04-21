<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Imports\PublikasiImport;
use App\Models\PublikasiDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PublikasiController extends Controller
{
    public function index()
    {
        $publikasi = PublikasiDosen::where('dosen_id', Auth::user()->profileDosen->id)
            ->when(request('search'), fn($q, $s) => $q->where('judul', 'like', "%{$s}%"))
            ->when(request('kategori'), fn($q, $k) => $q->where('jenis_publikasi', $k))
            ->when(request('tahun'), fn($q, $t) => $q->where('tahun', $t))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'  => PublikasiDosen::where('dosen_id', Auth::user()->profileDosen->id)->count(),
            'jurnal' => PublikasiDosen::where('dosen_id', Auth::user()->profileDosen->id)->where('jenis_publikasi', 'jurnal')->count(),
            'buku'   => PublikasiDosen::where('dosen_id', Auth::user()->profileDosen->id)->where('jenis_publikasi', 'buku')->count(),
            'haki'   => PublikasiDosen::where('dosen_id', Auth::user()->profileDosen->id)->where('jenis_publikasi', 'haki')->count(),
        ];

        return view('dosen.publikasi.index', compact('publikasi', 'stats'));
    }

    public function create()
    {
        return view('dosen.publikasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'jenis_publikasi' => 'required|in:jurnal,buku,haki',
            'tahun'           => 'required|integer|min:1900|max:' . date('Y'),
            'penerbit'        => 'nullable|string|max:255',
            'url'             => 'nullable|url:http,https|max:500',
        ]);

        $validated['dosen_id'] = Auth::user()->profileDosen->id;

        PublikasiDosen::create($validated);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan.');
    }

    public function show(PublikasiDosen $publikasi)
    {
        $this->authorizeOwner($publikasi);

        return view('dosen.publikasi.show', compact('publikasi'));
    }

    public function edit(PublikasiDosen $publikasi)
    {
        $this->authorizeOwner($publikasi);

        return view('dosen.publikasi.edit', compact('publikasi'));
    }

    public function update(Request $request, PublikasiDosen $publikasi)
    {
        $this->authorizeOwner($publikasi);

        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'jenis_publikasi' => 'required|in:jurnal,buku,haki',
            'tahun'           => 'required|integer|min:1900|max:' . date('Y'),
            'penerbit'        => 'nullable|string|max:255',
            'url'             => 'nullable|url:http,https|max:500',
        ]);

        $publikasi->update($validated);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function destroy(PublikasiDosen $publikasi)
    {
        $this->authorizeOwner($publikasi);

        $publikasi->delete();

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(
                new PublikasiImport((int) Auth::user()->profileDosen->id),
                $validated['file']
            );

            return back()->with('success', 'Import data publikasi berhasil.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    private function authorizeOwner(PublikasiDosen $publikasi): void
    {
        if ($publikasi->dosen_id !== Auth::user()->profileDosen->id) {
            abort(403);
        }
    }
}
