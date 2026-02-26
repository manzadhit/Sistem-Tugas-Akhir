<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\ProfileMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get("search");
        $status = $request->get('status');
        $angkatan = $request->get('angkatan');

        $daftarMahasiswa = ProfileMahasiswa::query()
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status_akademik', $status))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $stats = [
            'total' => ProfileMahasiswa::count(),
            'aktif' => ProfileMahasiswa::where('status_akademik', 'aktif')->count(),
            'cuti' => ProfileMahasiswa::where('status_akademik', 'cuti')->count(),
            'lulus' => ProfileMahasiswa::where('status_akademik', 'lulus')->count(),
            'nonaktif' => ProfileMahasiswa::where('status_akademik', 'nonaktif')->count(),
            'dropout' => ProfileMahasiswa::where('status_akademik', 'dropout')->count(),
        ];

        return view('admin.mahasiswa.list-mahasiswa', compact('daftarMahasiswa', 'stats'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create-mahasiswa');
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $user = User::create([
            'username' => $request->nim,
            'email' => null,
            'password' => bcrypt($request->nim),
            'role' => 'mahasiswa',
        ]);

        $user->profileMahasiswa()->create([
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
            'ipk' => $request->ipk,
            'no_telp' => $request->no_telp,
            'status_akademik' => $request->status_akademik,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Akun mahasiswa {$request->nama_lengkap} (NIM: {$request->nim}) berhasil dibuat. Password default: NIM.");
    }

    public function show($id)
    {
        $mhs = ProfileMahasiswa::with([
            'dosenPembimbing' => fn($q) => $q->with('dosen')->orderBy('jenis_pembimbing', 'asc'),
            'tugasAkhir.ujian.jadwalUjian',
        ])->findOrFail($id);

        $ujianList = $mhs->tugasAkhir
            ? $mhs->tugasAkhir->ujian->keyBy('jenis_ujian')
            : collect();

        return view('admin.mahasiswa.detail-mahasiswa', compact('mhs', 'ujianList'));
    }

    public function edit($id)
    {
        $mhs = ProfileMahasiswa::findOrFail($id);
        return view('admin.mahasiswa.edit-mahasiswa', compact('mhs'));
    }

    public function update(UpdateMahasiswaRequest $request, $id)
    {
        $mhs = ProfileMahasiswa::findOrFail($id);

        $mhs->update([
            'nama_lengkap' => $request->nama_lengkap,
            'angkatan' => $request->angkatan,
            'jurusan' => $request->jurusan,
            'program_studi' => $request->program_studi,
            'ipk' => $request->ipk,
            'no_telp' => $request->no_telp,
            'status_akademik' => $request->status_akademik,
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', "Data mahasiswa {$mhs->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $mhs = ProfileMahasiswa::findOrFail($id);
        $nama = $mhs->nama_lengkap;

        $mhs->user?->delete();

        $mhs->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Akun mahasiswa {$nama} berhasil dihapus.");
    }
}
