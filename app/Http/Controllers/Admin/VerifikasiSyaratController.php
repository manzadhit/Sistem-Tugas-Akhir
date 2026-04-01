<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenUjian;
use App\Models\PeriodeAkademik;
use App\Models\ProfileDosen;
use App\Models\UndanganUjian;
use App\Models\Ujian;
use App\Notifications\NewUjianSyaratSubmission;
use App\Notifications\UjianInvitationSent;
use App\Notifications\UjianSyaratReviewed;
use App\Services\Admin\UndanganPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiSyaratController extends Controller
{
  public function __construct(private UndanganPdfService $pdfService)
  {
  }

  public function index(Request $request)
  {
    $statsQuery = Ujian::query();

    if ($request->filled('jenis')) {
      $statsQuery->where('jenis_ujian', $request->jenis);
    }

    $totalPengajuan = (clone $statsQuery)
      ->whereIn('status', ['menunggu_verifikasi_syarat', 'menunggu_undangan'])
      ->count();

    $totalTerverifikasi = (clone $statsQuery)
      ->whereIn('status', [
        'menunggu_hasil',
        'revisi_hasil',
        'menunggu_verifikasi_hasil',
        'selesai',
      ])
      ->count();

    $query = Ujian::with([
      'tugasAkhir.mahasiswa',
      'dokumenUjian' => function ($q) {
        $q->where('kategori', 'syarat')->where('status', 'pending');
      }
    ])
      ->whereIn('status', ['menunggu_verifikasi_syarat', 'menunggu_undangan']);

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

    return view('admin.syarat-ujian.list-mahasiswa', compact('ujians', 'totalPengajuan', 'totalTerverifikasi'));
  }

  public function detail($id)
  {
    $ujian = Ujian::with([
      'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
      'tugasAkhir.mahasiswa.dosenPenguji.dosen',
      'dokumenUjian' => fn($q) => $q->where('kategori', 'syarat'),
      'jadwalUjian',
    ])->findOrFail($id);

    request()->user()->unreadNotifications()
      ->where('type', NewUjianSyaratSubmission::class)
      ->where('data->ujian_id', $ujian->id)
      ->update(['read_at' => now()]);

    return view('admin.syarat-ujian.detail-verifikasi', compact('ujian'));
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
        ->where('kategori', 'syarat')
        ->update([
          'status' => $data['status'],
          'catatan' => $data['catatan'] ?? null,
        ]);

      if ($data['status'] === 'tolak') {
        $adaTolak = true;
      }
    }

    if ($adaTolak) {
      $ujian->update(['status' => 'revisi_syarat']);
      $ujian->loadMissing('tugasAkhir.mahasiswa.user');
      $ujian->tugasAkhir->mahasiswa?->user?->notify(new UjianSyaratReviewed($ujian));

      return redirect()
        ->back()
        ->with('warning', 'Beberapa berkas ditolak. Pengajuan dikembalikan ke mahasiswa.');
    }

    $ujian->update(['status' => 'menunggu_undangan']);
    $ujian->loadMissing('tugasAkhir.mahasiswa.user');
    $ujian->tugasAkhir->mahasiswa?->user?->notify(new UjianSyaratReviewed($ujian));

    return redirect()
      ->route('admin.ujian.syarat.undangan', $ujian->id)
      ->with('success', 'Semua berkas di-ACC. Silakan buat undangan ujian.');
  }

  public function showUndangan($id)
  {
    $ujian = Ujian::with([
      'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
      'tugasAkhir.mahasiswa.dosenPenguji.dosen',
      'jadwalUjian',
      'undanganUjian',
    ])->findOrFail($id);

    $ketuaSidang = $ujian->tugasAkhir->mahasiswa->dosenPenguji
      ->where('jenis_penguji', 'penguji_1')->first()?->dosen;

    $ketuaJurusan = ProfileDosen::whereHas('user', function ($q) {
      $q->where('role', 'kajur');
    })->first();

    $sekretarisJurusan = ProfileDosen::whereHas('user', function ($q) {
      $q->where('role', 'sekjur');
    })->first();

    $periodeAkademik = PeriodeAkademik::orderByRaw("CASE status WHEN 'aktif' THEN 0 WHEN 'draft' THEN 1 ELSE 2 END")
      ->orderByDesc('mulai_at')
      ->get();

    return view('admin.syarat-ujian.undangan', compact('ujian', 'ketuaJurusan', 'sekretarisJurusan', 'ketuaSidang', 'periodeAkademik'));
  }

  public function storeUndangan(Request $request, $id)
  {
    $request->validate([
      'nomor_surat' => 'required|string|max:100',
      'hal' => 'required|string|max:255',
      'tanggal_surat' => 'required|date',
      'periode_akademik_id' => 'required|exists:periode_akademik,id',
      'ketua_sidang_id' => 'nullable|exists:profile_dosen,id',
      'sekretaris_sidang_id' => 'nullable|exists:profile_dosen,id',
    ]);

    $ujian = Ujian::with([
      'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
      'tugasAkhir.mahasiswa.dosenPenguji.dosen',
      'jadwalUjian',
    ])->findOrFail($id);

    $sekretarisJurusan = ProfileDosen::whereHas('user', function ($q) {
      $q->where('role', 'sekjur');
    })->first();

    $sekretarisSidang = ProfileDosen::find($request->sekretaris_sidang_id);

    try {
      $data = $this->pdfService->buildData($ujian, [
        'nomor' => $request->nomor_surat,
        'hal' => $request->hal,
        'tanggal_surat' => \Carbon\Carbon::parse($request->tanggal_surat)
          ->locale('id')->isoFormat('D MMMM Y'),
        'sekretaris' => $sekretarisSidang?->nama_lengkap ?? '-',
        'penandatangan' => [
          'nama' => $sekretarisJurusan?->nama_lengkap ?? '-',
          'nip' => $sekretarisJurusan?->nip ?? '-',
        ],
      ]);

      $pdf = $this->pdfService->generate($data);
      $fileName = 'undangan_seminar_' . $ujian->jenis_ujian . '_' . $ujian->tugasAkhir->mahasiswa->nim . '_' . $ujian->tugasAkhir->mahasiswa->nama_lengkap . '.pdf';
      $filePath = 'undangan/' . $fileName;
      Storage::put($filePath, $pdf->output());

      UndanganUjian::updateOrCreate(
        ['ujian_id' => $ujian->id],
        [
          'nomor_surat' => $request->nomor_surat,
          'hal' => $request->hal,
          'tanggal_surat' => $request->tanggal_surat,
          'ketua_sidang_id' => $request->ketua_sidang_id,
          'sekretaris_sidang_id' => $request->sekretaris_sidang_id,
          'file_path' => $filePath,
          'status' => 'draft',
        ]
      );

      $ujian->update(['periode_akademik_id' => $request->periode_akademik_id]);

      return redirect()
        ->route('admin.ujian.syarat.undangan', $id)
        ->with('success', 'Undangan berhasil di-generate');
    } catch (\Throwable $th) {
      return back()->with('error', $th->getMessage());
    }
  }

  public function kirimUndangan($id)
  {
    $ujian = Ujian::with([
      'undanganUjian',
      'tugasAkhir.mahasiswa.user',
      'tugasAkhir.mahasiswa.dosenPembimbing.dosen.user',
      'tugasAkhir.mahasiswa.dosenPenguji.dosen.user',
    ])->findOrFail($id);

    $ujian->undanganUjian->update(['status' => 'terkirim']);
    $newStatus = $ujian->jenis_ujian === 'skripsi' ? 'menunggu_nilai' : 'menunggu_hasil';
    $ujian->update(['status' => $newStatus]);

    $mahasiswa = $ujian->tugasAkhir->mahasiswa;

    $recipients = collect([
      $mahasiswa?->user,
      ...$mahasiswa->dosenPembimbing
        ->pluck('dosen.user')
        ->filter()
        ->all(),
      ...$mahasiswa->dosenPenguji
        ->pluck('dosen.user')
        ->filter()
        ->all(),
    ])->filter()->unique('id');

    $recipients->each->notify(new UjianInvitationSent($ujian));

    return redirect()
      ->back()
      ->with('show_success_modal', true);
  }
}
