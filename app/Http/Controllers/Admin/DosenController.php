<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Models\ProfileDosen;
use App\Models\PublikasiDosen;
use App\Models\User;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatan = $request->get('jabatan');
        $status = $request->get('status');

        $daftarDosen = ProfileDosen::query()
            ->with('pembimbingMahasiswa')
            ->withCount('publikasi')
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nidn', 'like', "%{$search}%"))
            ->when($jabatan, fn($q) => $q->where('jabatan_fungsional', $jabatan))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();


        $stats = [
            'total' => ProfileDosen::count(),
            'aktif' => ProfileDosen::where('status', 'aktif')->count(),
            'total_publikasi' => PublikasiDosen::count(),
        ];

        return view('admin.dosen.list-dosen', compact('daftarDosen', 'stats'));
    }

    public function create()
    {
        return view('admin.dosen.create-dosen');
    }

    public function store(StoreDosenRequest $request)
    {
        $user = User::create([
            'username' => $request->nidn,
            'email' => null,
            'password' => bcrypt($request->nidn),
            'role' => 'dosen',
        ]);

        $user->profileDosen()->create([
            'nidn' => $request->nidn,
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'program_studi' => $request->program_studi,
            'keahlian' => $request->keahlian,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', "Akun dosen {$request->nama_lengkap} (NIDN: {$request->nidn}) berhasil dibuat. Password default: NIDN.");
    }

    public function show($id)
    {
        $dosen = ProfileDosen::with([
            'user',
            'pembimbingMahasiswa.mahasiswa',
            'pengujiMahasiswa.mahasiswa',
        ])->findOrFail($id);

        return view('admin.dosen.detail-dosen', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = ProfileDosen::findOrFail($id);
        return view('admin.dosen.edit-dosen', compact('dosen'));
    }

    public function update(UpdateDosenRequest $request, $id)
    {
        $dosen = ProfileDosen::findOrFail($id);

        $dosen->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'program_studi' => $request->program_studi,
            'keahlian' => $request->keahlian,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', "Data dosen {$dosen->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $dosen = ProfileDosen::findOrFail($id);
        $nama = $dosen->nama_lengkap;

        $dosen->user?->delete();

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', "Akun dosen {$nama} berhasil dihapus.");
    }
}
