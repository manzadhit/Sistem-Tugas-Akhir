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
          'active' => request()->is('dosen/bimbingan*'),
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
      [
          'href' => route('dosen.nilai.index'),
          'icon' => 'fas fa-edit',
          'label' => 'Input Nilai',
          'active' => request()->routeIs('dosen.nilai.index'),
      ],
  
      ['section' => 'Publikasi'],
      [
          'href' => route('dosen.publikasi.index'),
          'icon' => 'fas fa-book',
          'label' => 'Publikasi Saya',
          'active' => request()->routeIs('dosen.publikasi.*'),
      ],
  
      ['section' => 'Akun'],
      [
          'href' => route('dosen.profile.edit'),
          'icon' => 'fas fa-user-circle',
          'label' => 'Profil Saya',
          'active' => request()->routeIs('dosen.profile.*'),
      ],
  ]" />
