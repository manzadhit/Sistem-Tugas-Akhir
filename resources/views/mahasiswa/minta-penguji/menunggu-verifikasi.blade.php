{{-- Tampilan saat KajurSubmission status = 'pending' --}}
<div class="mx-auto mb-8 max-w-3xl overflow-hidden rounded-xl bg-white shadow-sm">
  <!-- Status Header -->
  <div class="border-b border-slate-200 bg-slate-50 px-8 py-6 text-center text-slate-900">
    <h3 class="mb-1 text-xl font-bold">Status Pengajuan Penguji</h3>
    <p class="text-[0.95rem] text-slate-500">Dokumen Anda sudah diterima dan sedang diverifikasi.</p>
    <div class="mt-3 flex flex-wrap items-center justify-center gap-3">
      <span
        class="inline-flex items-center rounded-full bg-blue-100 px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold text-blue-800">
        Menunggu Verifikasi
      </span>
      <span class="text-[0.85rem] text-slate-500">Estimasi: 1–2 hari kerja</span>
    </div>
  </div>
  <!-- Status Body -->
  <div class="p-8 text-center">
    <div class="flex flex-col items-center gap-3">
      <div class="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600" aria-hidden="true">
      </div>
      <div class="text-[0.85rem] text-slate-500">Sedang menunggu verifikasi Kajur</div>
    </div>
  </div>
</div>
