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
      ['href' => '#', 'icon' => 'fas fa-clipboard-list', 'label' => 'Jadwal Ujian'],
      ['href' => '#', 'icon' => 'fas fa-edit', 'label' => 'Input Nilai'],
  
      ['section' => 'Publikasi'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Publikasi Saya'],
  ]" />
