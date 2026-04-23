  <x-role-sidebar title="Panel Admin" subtitle="Teknik Informatika" :items="[
      [
          'href' => route('admin.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('admin.dashboard'),
      ],
  
      ['section' => 'Ujian'],
      [
          'href' => route('admin.ujian.syarat.index'),
          'icon' => 'fas fa-file-signature',
          'label' => 'Verifikasi Syarat Ujian',
          'active' => request()->is('admin/ujian/verifikasi-syarat*'),
          'badge' => $countSyarat > 0 ? $countSyarat : null,
      ],
      [
          'href' => route('admin.ujian.hasil.index'),
          'icon' => 'fas fa-clipboard-check',
          'label' => 'Verifikasi Hasil Ujian',
          'active' => request()->is('admin/ujian/verifikasi-hasil*'),
          'badge' => $countHasil > 0 ? $countHasil : null,
      ],
  
      ['section' => 'Kelola Data'],
      [
          'href' => route('admin.mahasiswa.index'),
          'icon' => 'fas fa-user-graduate',
          'label' => 'Kelola Mahasiswa',
          'active' => request()->is('admin/mahasiswa*'),
      ],
      [
          'href' => route('admin.dosen.index'),
          'icon' => 'fas fa-chalkboard-teacher',
          'label' => 'Kelola Dosen',
          'active' => request()->is('admin/dosen*'),
      ],
      [
          'href' => route('admin.mata-kuliah.index'),
          'icon' => 'fas fa-book-open',
          'label' => 'Kelola Mata Kuliah',
          'active' => request()->is('admin/mata-kuliah*'),
      ],
      [
          'href' => route('admin.publikasi.index'),
          'icon' => 'fas fa-book',
          'label' => 'Kelola Publikasi Dosen',
          'active' => request()->is('admin/publikasi*'),
      ],
      [
          'href' => route('admin.periode.index'),
          'icon' => 'fas fa-calendar-alt',
          'label' => 'Kelola Periode Akademik',
          'active' => request()->is('admin/periode*'),
      ],
  
      ['section' => 'Lainnya'],
      [
          'href' => route('admin.profile.edit'),
          'icon' => 'fas fa-user-shield',
          'label' => 'Profil Saya',
          'active' => request()->routeIs('admin.profile.*'),
      ],
  ]" />
