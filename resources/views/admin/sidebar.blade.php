  <x-role-sidebar title="Panel Admin" subtitle="Teknik Informatika" :items="[
      [
          'href' => route('admin.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('admin.dashboard'),
      ],
  
      ['section' => 'Kelola Data'],
      ['href' => '#', 'icon' => 'fas fa-user-graduate', 'label' => 'Kelola Mahasiswa'],
      ['href' => '#', 'icon' => 'fas fa-chalkboard-teacher', 'label' => 'Kelola Dosen'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Kelola Publikasi Dosen'],
  
      ['section' => 'Verifikasi Syarat'],
      [
          'href' => route('admin.ujian.verifikasi', 'proposal'),
          'icon' => 'fas fa-file-signature',
          'label' => 'Proposal',
          'active' => request()->is('admin/ujian/proposal*'),
      ],
      ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open', 'label' => 'Skripsi'],
  
      ['section' => 'Verifikasi Hasil'],
      [
          'href' => route('admin.ujian.hasil-ujian.index', 'proposal'),
          'icon' => 'fas fa-file-signature',
          'label' => 'Proposal',
          'active' => request()->is('admin/ujian/proposal/hasil-ujian', 'admin/ujian/proposal/*/hasil-ujian')
      ],
      ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open', 'label' => 'Skripsi'],
  ]" />
