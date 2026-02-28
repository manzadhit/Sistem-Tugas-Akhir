<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;

class VerifikasiHasilController extends Controller
{
  public function index(Request $request)
  {
    $query = Ujian::with([
      'tugasAkhir.mahasiswa',
      'dokumenUjian' => function ($q) {
        $q->where('kategori', 'hasil')->where('status', 'pending');
      }
    ])
      ->whereIn('status', ['menunggu_verifikasi_hasil']);

    if ($request->filled('jenis')) {
      $query->where('jenis_ujian', $request->jenis);
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->whereHas('tugasAkhir.mahasiswa', function ($q) use ($search) {
        $q->where('nama_lengkap', 'like', "%{$search}%")
          ->orWhere('nim', 'like', "%{$search}%");
      });
    }

    $ujians = $query->paginate(10)->withQueryString();

    return view('admin.pasca-ujian.list-mahasiswa', compact('ujians'));
  }

  public function detail($id)
  {
    $ujian = Ujian::with([
      'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
      'tugasAkhir.mahasiswa.dosenPenguji.dosen',
      'dokumenUjian' => fn($q) => $q->where('kategori', 'hasil'),
      'jadwalUjian',
    ])
      ->findOrFail($id);

    return view('admin.pasca-ujian.detail-verifikasi', compact('ujian'));
  }

  public function proses(Request $request, $id)
  {
    $request->validate([
      'dokumen.*.status' => 'required|in:acc,tolak',
      'dokumen.*.catatan' => 'nullable|string|max:500',
    ]);

    $ujian = Ujian::findOrFail($id);

    $adaTolak = false;

    foreach ($request->input('dokumen', []) as $dokumenId => $data) {
      DokumenUjian::where('id', $dokumenId)
        ->where('ujian_id', $ujian->id)
        ->where('kategori', 'hasil')
        ->update([
          'status' => $data['status'],
          'catatan' => $data['catatan'] ?? null,
        ]);

      if ($data['status'] === 'tolak') {
        $adaTolak = true;
      }
    }

    if ($adaTolak) {
      $ujian->update(['status' => 'revisi_hasil']);

      return redirect()
        ->back()
        ->with('warning', 'Beberapa berkas hasil ujian ditolak. Pengajuan dikembalikan ke mahasiswa untuk direvisi.');
    }

    $ujian->update(['status' => 'selesai']);

    return redirect()
      ->back()
      ->with('show_success_modal', true)
      ->with('success', 'Semua berkas hasil ujian di-ACC. Ujian dinyatakan selesai.');
  }
}
