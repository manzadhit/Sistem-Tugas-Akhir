<x-role-sidebar title="Ketua Jurusan" subtitle="Informatika" :items="[
    [
        'href' => route('kajur.dashboard'),
        'icon' => 'fas fa-chart-line',
        'label' => 'Dashboard',
        'active' => request()->routeIs('kajur.dashboard'),
    ],
    [
        'href' => route('kajur.permintaan-pembimbing'),
        'icon' => 'fas fa-user-plus',
        'label' => 'Permintaan Pembimbing',
        'active' => request()->routeIs('kajur.permintaan-pembimbing', 'kajur.penetapan-pembimbing'),
    ],
    [
        'href' => route('kajur.permintaan-penguji.index'),
        'icon' => 'fas fa-clipboard-check',
        'label' => 'Permintaan Penguji',
        'active' => request()->routeIs('kajur.permintaan-penguji.index', 'kajur.penetapan-penguji'),
    ],
    [
        'href' => route('kajur.persetujuan-kajur.index'),
        'icon' => 'fas fa-file-signature',
        'label' => 'Persetujuan Kajur',
        'active' => request()->routeIs('kajur.persetujuan-kajur.*'),
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
        'active' => request()->routeIs('dosen.publikasi.index'),
    ],
    ['section' => 'Akun'],
    [
        'href' => route('kajur.profile.edit'),
        'icon' => 'fas fa-user-circle',
        'label' => 'Profil Saya',
        'active' => request()->routeIs('kajur.profile.*'),
    ],
]" />
