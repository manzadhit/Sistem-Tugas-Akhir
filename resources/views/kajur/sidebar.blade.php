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
    ['href' => '#', 'icon' => 'fas fa-clipboard-check', 'label' => 'Permintaan Penguji'],
]" />
