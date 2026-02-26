@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Mahasiswa</h1>
      <p class="text-gray-500">
        Kelola data mahasiswa Teknik Informatika
      </p>
    </div>
    <button
      class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
      <i class="fas fa-plus"></i>
      Tambah Mahasiswa
    </button>
  </div>

  <!-- Stats Summary -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-users"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Total Mahasiswa</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-user-check"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Mahasiswa Aktif</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['aktif'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Mahasiswa Lulus</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['lulus'] }}</div>
      </div>
    </div>
  </div>

  <!-- Data Table Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h3 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa</h3>
      <form action="{{ route('admin.mahasiswa.index') }}" method="GET" id="filterForm"
        class="flex flex-wrap items-center gap-4">
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="Cari nama atau NIM..." id="searchInput" name="search"
            value="{{ request('search') }}"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors" />
        </div>
        <div class="relative">
          <select
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600"
            name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="cuti" {{ request('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
            <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="dropout" {{ request('status') === 'dropout' ? 'selected' : '' }}>Dropout</option>
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        <div class="relative">
          <select
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600"
            name="angkatan" onchange="this.form.submit()">
            <option value="">Semua Angkatan</option>
            <option value="2024" {{ request('angkatan') == '2024' ? 'selected' : '' }}>2024</option>
            <option value="2023" {{ request('angkatan') == '2023' ? 'selected' : '' }}>2023</option>
            <option value="2022" {{ request('angkatan') == '2022' ? 'selected' : '' }}>2022</option>
            <option value="2021" {{ request('angkatan') == '2021' ? 'selected' : '' }}>2021</option>
            <option value="2020" {{ request('angkatan') == '2020' ? 'selected' : '' }}>2020</option>
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
      </form>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse" id="studentTable">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">No</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Mahasiswa</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Angkatan</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">IPK</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Kontak</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($daftarMahasiswa as $mhs)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                    {{ $mhs->nama_lengkap[0] }}</div>
                  <div>
                    <div class="font-medium text-gray-900">{{ $mhs->nama_lengkap }}</div>
                    <div class="text-xs text-gray-500">{{ $mhs->nim }}</div>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-700">{{ $mhs->angkatan }}</td>
              <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ $mhs->ipk }}</td>
              <td class="px-5 py-4 text-sm text-gray-700">{{ $mhs->no_telp }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                  {{ $mhs->status_akademik === 'aktif'
                      ? 'bg-emerald-100 text-emerald-700'
                      : ($mhs->status_akademik === 'cuti'
                          ? 'bg-amber-100 text-amber-700'
                          : ($mhs->status_akademik === 'lulus'
                              ? 'bg-blue-100 text-blue-700'
                              : ($mhs->status_akademik === 'nonaktif'
                                  ? 'bg-gray-100 text-gray-700'
                                  : 'bg-red-100 text-red-700'))) }}">
                  {{ ucfirst($mhs->status_akademik) }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                    class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors"
                    title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
                    class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#"
                    class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors"
                    title="Hapus">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-4 text-center text-gray-500">Tidak ada data mahasiswa.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-4">
    {{ $daftarMahasiswa->links() }}
  </div>
@endsection
