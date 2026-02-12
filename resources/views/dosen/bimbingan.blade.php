@extends('layouts.app')

@section('title', 'Dosen-Bimbingan')

@section('sidebar')
  @include('dosen.sidebar')
@endsection

@section('content')
  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Mahasiswa Bimbingan</h1>
    <p class="text-base text-gray-500">
      Kelola dan pantau perkembangan mahasiswa bimbingan Anda
    </p>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
    <div
      class="bg-white rounded-xl p-6 shadow-sm flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="w-14 h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
        <i class="fas fa-users"></i>
      </div>
      <div class="flex-1">
        <div class="text-sm text-gray-500 mb-1">Total Mahasiswa</div>
        <div class="text-3xl font-bold text-gray-900">{{ $totalMahasiswaBimbingan }}</div>
      </div>
    </div>
    <div
      class="bg-white rounded-xl p-6 shadow-sm flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="w-14 h-14 rounded-xl bg-orange-200 text-orange-600 flex items-center justify-center text-2xl">
        <i class="fas fa-clock"></i>
      </div>
      <div class="flex-1">
        <div class="text-sm text-gray-500 mb-1">Menunggu Review</div>
        <div class="text-3xl font-bold text-gray-900">{{ $pendingSubmissions->total() }}</div>
      </div>
    </div>
  </div>

  <!-- Filter Section -->
  <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
      <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-700">Cari Mahasiswa</label>
        <input type="text"
          class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10"
          placeholder="Nama atau NIM..." />
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-700">Tahap</label>
        <select
          class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10">
          <option value="">Semua Tahap</option>
          <option value="proposal">Proposal</option>
          <option value="hasil">Hasil</option>
          <option value="skripsi">Skripsi</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">No
            </th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">NIM
            </th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">
              Nama</th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">
              Tanggal Submit</th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">
              Judul</th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">
              Jenis</th>
            <th class="p-4 text-left text-sm font-semibold text-gray-700 border-b-2 border-gray-200 whitespace-nowrap">
              Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Row 1 -->
          @foreach ($pendingSubmissions as $submission)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $loop->iteration }}</td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $submission->tugasAkhir->mahasiswa->nim }}
              </td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
                {{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}</td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
                {{ $submission->created_at->format('d M Y') }}</td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
                <div class="max-w-[300px] whitespace-normal leading-snug">
                  {{ $submission->tugasAkhir->judul }}
                </div>
              </td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
                <span
                  class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 whitespace-nowrap">{{ $submission->tugasAkhir->tahapan }}</span>
              </td>
              <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
                <div class="flex gap-2">
                  <a href="detail-bimbingan.html"
                    class="w-8 h-8 rounded-md flex items-center justify-center bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all text-sm">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
              </td>
            </tr>
          @endforeach

        </tbody>
      </table>


    </div>

  </div>
  <div class="mt-4">
    {{ $pendingSubmissions->links('pagination::tailwind') }}
  </div>
@endsection
