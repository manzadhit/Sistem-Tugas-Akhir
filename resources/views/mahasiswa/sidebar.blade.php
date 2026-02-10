  <x-role-sidebar
    title="Portal Mahasiswa"
    subtitle="Teknik Informatika"
    :items="[
      ['href' => route('mahasiswa.dashboard'), 'icon' => 'fas fa-home', 'label' => 'Dashboard', 'active' => request()->routeIs('mahasiswa.dashboard')],

      ['section' => 'Bimbingan'],
      ['href' => route('mahasiswa.bimbingan.index'), 'icon' => 'fas fa-file-signature', 'label' => 'Bimbingan Proposal', 'active' => request()->routeIs('mahasiswa.bimbingan.index')],
      ['href' => '#', 'icon' => 'fas fa-chart-line',     'label' => 'Bimbingan Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open',      'label' => 'Bimbingan Skripsi'],

      ['section' => 'Ujian'],
      ['href' => '#', 'icon' => 'fas fa-graduation-cap',   'label' => 'Ujian Proposal'],
      ['href' => '#', 'icon' => 'fas fa-clipboard-check',  'label' => 'Ujian Hasil'],
      ['href' => '#', 'icon' => 'fas fa-user-graduate',    'label' => 'Ujian Skripsi'],

      ['section' => 'Lainnya'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Panduan'],
    ]"
  />