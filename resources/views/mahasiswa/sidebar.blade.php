  <x-role-sidebar title="Portal Mahasiswa" subtitle="Teknik Informatika" :items="[
      [
          'href' => route('mahasiswa.dashboard'),
          'icon' => 'fas fa-home',
          'label' => 'Dashboard',
          'active' => request()->routeIs('mahasiswa.dashboard'),
      ],
  
      ['section' => 'Bimbingan'],
      [
          'href' => route('mahasiswa.bimbingan.index', ['jenis' => 'proposal']),
          'icon' => 'fas fa-file-signature',
          'label' => 'Bimbingan Proposal',
          'active' => request()->is('mahasiswa/bimbingan/proposal*'),
      ],
      [
          'href' => route('mahasiswa.bimbingan.index', ['jenis' => 'hasil']),
          'icon' => 'fas fa-chart-line',
          'label' => 'Bimbingan Hasil',
          'active' => request()->is('mahasiswa/bimbingan/hasil*'),
      ],
      [
          'href' => route('mahasiswa.bimbingan.index', ['jenis' => 'skripsi']),
          'icon' => 'fas fa-book-open',
          'label' => 'Bimbingan Skripsi',
          'active' => request()->is('mahasiswa/bimbingan/skripsi*'),
      ],
  
      ['section' => 'Ujian'],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'proposal']),
          'icon' => 'fas fa-graduation-cap',
          'label' => 'Ujian Proposal',
          'active' => request()->is('mahasiswa/ujian/proposal*'),
      ],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'hasil']),
          'icon' => 'fas fa-clipboard-check',
          'label' => 'Ujian Hasil',
          'active' => request()->is('mahasiswa/ujian/hasil*'),
      ],
      [
          'href' => route('mahasiswa.ujian', ['jenis' => 'skripsi']),
          'icon' => 'fas fa-user-graduate',
          'label' => 'Ujian Skripsi',
          'active' => request()->is('mahasiswa/ujian/skripsi*'),
      ],
  
      ['section' => 'Lainnya'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Panduan'],
  ]" />
