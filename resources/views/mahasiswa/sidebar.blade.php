  <x-role-sidebar title="Portal Mahasiswa" subtitle="Teknik Informatika" :items="[
      [
          'href' => route('mahasiswa.dashboard'),
          'icon' => 'fas fa-home',
          'label' => 'Dashboard',
          'active' => request()->routeIs('mahasiswa.dashboard'),
      ],
  
      ['section' => 'Bimbingan'],
      [
          'href' => route('mahasiswa.bimbingan.index'),
          'icon' => 'fas fa-file-signature',
          'label' => 'Bimbingan Proposal',
          'active' => request()->routeIs('mahasiswa.bimbingan.index'),
      ],
      ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Bimbingan Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open', 'label' => 'Bimbingan Skripsi'],
  
      ['section' => 'Ujian'],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'proposal']),
          'icon' => 'fas fa-graduation-cap',
          'label' => 'Ujian Proposal',
          'active' => request()->routeIs('mahasiswa.ujian') && request()->route('jenis') === 'proposal',
      ],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'hasil']),
          'icon' => 'fas fa-clipboard-check',
          'label' => 'Ujian Hasil',
          'active' => request()->routeIs('mahasiswa.ujian') && request()->route('jenis') === 'hasil',
      ],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'skripsi']),
          'icon' => 'fas fa-user-graduate',
          'label' => 'Ujian Skripsi',
          'active' => request()->routeIs('mahasiswa.ujian') && request()->route('jenis') === 'skripsi',
      ],
  
      ['section' => 'Lainnya'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Panduan'],
  ]" />
