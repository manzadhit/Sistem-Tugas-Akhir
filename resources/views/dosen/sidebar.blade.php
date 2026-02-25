  <x-role-sidebar title="Portal Dosen" :items="[
      [
          'href' => route('dosen.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('dosen.dashboard'),
      ],
  
      ['section' => 'Pembimbingan'],
      [
          'href' => route('dosen.bimbingan.index'),
          'icon' => 'fas fa-users',
          'label' => 'Mahasiswa Bimbingan',
          'active' => request()->routeIs(['dosen.bimbingan.index', 'dosen.bimbingan.detail']),
      ],
  
      ['section' => 'Pengujian'],
      [
          'href' => route('dosen.undangan.index'),
          'icon' => 'fas fa-envelope-open-text',
          'label' => 'Undangan',
          'active' => request()->routeIs('dosen.undangan.index'),
      ],
      [
          'href' => route('dosen.jadwal.index'),
          'icon' => 'fas fa-clipboard-list',
          'label' => 'Jadwal Ujian',
          'active' => request()->routeIs('dosen.jadwal.index'),
      ],
      ['href' => '#', 'icon' => 'fas fa-edit', 'label' => 'Input Nilai'],
  
      ['section' => 'Publikasi'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Publikasi Saya'],
  ]" />
