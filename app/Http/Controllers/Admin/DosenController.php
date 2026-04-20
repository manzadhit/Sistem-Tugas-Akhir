<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Imports\DosenImport;
use App\Models\MataKuliah;
use App\Models\ProfileDosen;
use App\Models\PublikasiDosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatan = $request->get('jabatan');
        $status = $request->get('status');

        $daftarDosen = ProfileDosen::query()
            ->withCount('publikasi')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nidn', 'like', "%{$search}%");
                });
            })
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
        $mataKuliahOptions = $this->mataKuliahOptions();
        $kajurExists = User::where('role', 'kajur')->exists();
        $sekjurExists = User::where('role', 'sekjur')->exists();

        return view('admin.dosen.create-dosen', compact('mataKuliahOptions', 'kajurExists', 'sekjurExists'));
    }

    public function store(StoreDosenRequest $request)
    {
        $user = User::create([
            'username' => $request->nidn,
            'email' => null,
            'password' => bcrypt($request->nidn),
            'must_change_password' => true,
            'role' => $request->role,
        ]);

        $user->profileDosen()->create([
            'nidn' => $request->nidn,
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'keahlian' => $request->keahlian,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'sinta_score_3y' => $request->validated('sinta_score_3y') ?? 0,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
        ]);

        $user->profileDosen->mataKuliah()->sync($request->validated('mata_kuliah_ids', []));

        return redirect()->route('admin.dosen.index')
            ->with('success', "Akun {$request->role} {$request->nama_lengkap} (NIDN: {$request->nidn}) berhasil dibuat. Password default: NIDN.");
    }

    public function show($id)
    {
        $dosen = ProfileDosen::with([
            'user',
            'mataKuliah',
            'pembimbingMahasiswa.mahasiswa',
            'pengujiMahasiswa.mahasiswa',
        ])->findOrFail($id);

        return view('admin.dosen.detail-dosen', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = ProfileDosen::with(['mataKuliah', 'user'])->findOrFail($id);
        $mataKuliahOptions = $this->mataKuliahOptions();

        // Exclude the current dosen's user from the uniqueness check
        $kajurExists = User::where('role', 'kajur')
            ->where('id', '!=', $dosen->user_id)
            ->exists();
        $sekjurExists = User::where('role', 'sekjur')
            ->where('id', '!=', $dosen->user_id)
            ->exists();

        return view('admin.dosen.edit-dosen', compact('dosen', 'mataKuliahOptions', 'kajurExists', 'sekjurExists'));
    }

    public function update(UpdateDosenRequest $request, $id)
    {
        $dosen = ProfileDosen::with('user')->findOrFail($id);

        $dosen->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'keahlian' => $request->keahlian,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'sinta_score_3y' => $request->validated('sinta_score_3y') ?? 0,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
        ]);

        // Update role on the user account
        if ($dosen->user) {
            $dosen->user->update(['role' => $request->role]);
        }

        $dosen->mataKuliah()->sync($request->validated('mata_kuliah_ids', []));

        return redirect()->route('admin.dosen.index')
            ->with('success', "Data dosen {$dosen->nama_lengkap} berhasil diperbarui.");
    }

    public function resetPassword($id)
    {
        $dosen = ProfileDosen::with('user')->findOrFail($id);

        if (! $dosen->user) {
            return redirect()->route('admin.dosen.index')
                ->with('error', "Akun login untuk dosen {$dosen->nama_lengkap} tidak ditemukan.");
        }

        $dosen->user->update([
            'password' => Hash::make($dosen->nidn),
            'must_change_password' => true,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', "Password dosen {$dosen->nama_lengkap} berhasil direset ke NIDN.");
    }

    protected function mataKuliahOptions(): array
    {
        return MataKuliah::orderBy('nama')
            ->get(['id', 'kode', 'nama'])
            ->map(fn($mataKuliah) => [
                'id' => (string) $mataKuliah->id,
                'label' => "{$mataKuliah->kode} - {$mataKuliah->nama}",
            ])
            ->values()
            ->all();
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

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new DosenImport, $validated['file']);

            return back()->with('success', 'Import data dosen berhasil.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
