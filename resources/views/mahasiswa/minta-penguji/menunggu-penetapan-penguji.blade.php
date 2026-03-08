{{-- Tampilan saat KajurSubmission status = 'acc' tetapi penguji belum ditetapkan --}}
<div class="mx-auto mb-8 max-w-3xl overflow-hidden rounded-xl bg-white shadow-sm">
  <div class="border-b border-emerald-200 bg-emerald-50 px-8 py-6 text-center text-slate-900">
    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-white">
      <i class="fas fa-file-circle-check text-3xl"></i>
    </div>
    <h3 class="mb-1 text-xl font-bold">Laporan Sudah Disetujui</h3>
    <p class="text-[0.95rem] text-slate-600">
      Dokumen laporan tugas akhir Anda telah diverifikasi dan disetujui oleh Ketua Jurusan.
    </p>
    <div class="mt-3 flex flex-wrap items-center justify-center gap-3">
      <span
        class="inline-flex items-center rounded-full bg-emerald-100 px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold text-emerald-800">
        Menunggu Penetapan Penguji
      </span>
      <span class="text-[0.85rem] text-slate-500">Silakan tunggu dosen penguji ditetapkan</span>
    </div>
  </div>

  <div class="p-8 text-center">
    <div class="flex flex-col items-center gap-4">
      <div class="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-emerald-500"
        aria-hidden="true"></div>
      <div class="space-y-1">
        <p class="text-sm font-medium text-slate-700">Ketua Jurusan sedang menetapkan dosen penguji untuk Anda.</p>
        <p class="text-[0.85rem] text-slate-500">Halaman ini akan berubah setelah penguji berhasil ditetapkan.</p>
      </div>
    </div>

    <div class="mt-8 flex flex-wrap justify-center gap-3 border-t border-gray-200 pt-6">
      <a href="{{ route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
        <i class="fas fa-arrow-left"></i>
        Kembali
      </a>
    </div>
  </div>
</div>
