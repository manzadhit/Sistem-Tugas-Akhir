  <x-role-sidebar title="Portal Dosen" :items="[
      [
          'href' => route('dosen.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('dosen.dashboard'),
      ],
  
      ['section' => 'Pembimbingan'],
      ['href' => route('dosen.bimbingan.index'), 'icon' => 'fas fa-users', 'label' => 'Mahasiswa Bimbingan', 'active' => request()->routeIs('dosen.bimbingan.index')],
  
      ['section' => 'Pengujian'],
      ['href' => '#', 'icon' => 'fas fa-envelope-open-text', 'label' => 'Undangan'],
      ['href' => '#', 'icon' => 'fas fa-clipboard-list', 'label' => 'Jadwal Ujian'],
      ['href' => '#', 'icon' => 'fas fa-edit', 'label' => 'Input Nilai'],
  
      ['section' => 'Publikasi'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Publikasi Saya'],
  ]" />