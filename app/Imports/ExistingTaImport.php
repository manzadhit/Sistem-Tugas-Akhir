<?php

namespace App\Imports;

use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\PeriodeAkademik;
use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use App\Models\TugasAkhir;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ExistingTaImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private string $defaultPasswordHash;

    public function __construct()
    {
        $this->defaultPasswordHash = Hash::make('12345@#');
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            $periodeAktifId = PeriodeAkademik::aktif()->value('id');

            foreach ($rows as $index => $row) {
                $baris = $index + 2;

                // parse data dari row CSV
                $nim                 = strtoupper(trim((string) $row['nim']));
                $namaLengkap         = strtoupper(trim((string) $row['nama_lengkap']));
                $peminatan           = strtoupper(trim((string) ($row['peminatan'] ?? ''))) ?: null;
                $judulTa             = trim((string) $row['judul_ta']);
                $tahap               = strtolower(trim((string) $row['tahap']));
                $proposalPeriodeAktif = strtolower(trim((string) ($row['proposal_periode_aktif'] ?? '')));
                $pembimbing1Nidn     = trim((string) $row['pembimbing_1_nidn']);
                $pembimbing2Nidn     = trim((string) $row['pembimbing_2_nidn']);
                $penguji1Nidn        = trim((string) ($row['penguji_1_nidn'] ?? ''));
                $penguji2Nidn        = trim((string) ($row['penguji_2_nidn'] ?? ''));
                $penguji3Nidn        = trim((string) ($row['penguji_3_nidn'] ?? ''));

                // extract angkatan dari NIM (E1E122xxx → 2022)
                preg_match('/^E1E1(\d{2})/i', $nim, $matches);
                $angkatan = 2000 + (int) $matches[1];

                // validasi kelengkapan penguji berdasarkan tahap
                $pengujiNidns = array_filter([$penguji1Nidn, $penguji2Nidn, $penguji3Nidn], fn($value) => $value !== '');
                $hasAnyPenguji = count($pengujiNidns) > 0;
                $allPengujiFilled = count($pengujiNidns) === 3;

                if ($tahap === 'proposal' && $hasAnyPenguji && ! $allPengujiFilled) {
                    throw new \RuntimeException("Baris {$baris}: data penguji untuk tahap proposal harus kosong semua atau terisi lengkap.");
                }

                if (in_array($tahap, ['hasil', 'skripsi', 'lulus'], true) && ! $allPengujiFilled) {
                    throw new \RuntimeException("Baris {$baris}: semua penguji wajib diisi untuk tahap {$tahap}.");
                }

                // validasi proposal_periode_aktif
                if (in_array($tahap, ['hasil', 'skripsi', 'lulus'], true) && ! in_array($proposalPeriodeAktif, ['ya', 'tidak'], true)) {
                    throw new \RuntimeException("Baris {$baris}: kolom proposal periode aktif wajib diisi ya atau tidak untuk tahap {$tahap}.");
                }

                if ($proposalPeriodeAktif === 'ya' && ! $periodeAktifId) {
                    throw new \RuntimeException("Baris {$baris}: tidak ada periode akademik aktif untuk menandai proposal periode aktif.");
                }

                // validasi NIDN tidak duplikat & dosen ada di DB
                $semuaNidn = array_filter([
                    $pembimbing1Nidn,
                    $pembimbing2Nidn,
                    $penguji1Nidn,
                    $penguji2Nidn,
                    $penguji3Nidn,
                ], fn($value) => $value !== '');

                if (count($semuaNidn) !== count(array_unique($semuaNidn))) {
                    throw new \RuntimeException("Baris {$baris}: NIDN pembimbing dan penguji tidak boleh duplikat.");
                }

                $dosenByNidn = ProfileDosen::whereIn('nidn', $semuaNidn)->get()->keyBy('nidn');

                foreach ($semuaNidn as $nidn) {
                    if (! $dosenByNidn->has($nidn)) {
                        throw new \RuntimeException("Baris {$baris}: dosen dengan NIDN {$nidn} tidak ditemukan.");
                    }
                }

                // create user & mahasiswa
                $user = User::where('username', $nim)->first();

                if ($user && $user->role !== 'mahasiswa') {
                    throw new \RuntimeException("Baris {$baris}: NIM {$nim} sudah dipakai akun non-mahasiswa.");
                }

                if (! $user) {
                    $user = User::create([
                        'username' => $nim,
                        'email' => null,
                        'password' => $this->defaultPasswordHash,
                        'must_change_password' => true,
                        'role' => 'mahasiswa',
                    ]);
                }

                $mahasiswa = ProfileMahasiswa::updateOrCreate(
                    ['nim' => $nim],
                    [
                        'user_id' => $user->id,
                        'nama_lengkap' => $namaLengkap,
                        'jurusan' => 'Informatika',
                        'peminatan' => $peminatan ?: null,
                        'angkatan' => (string) $angkatan,
                        'status_akademik' => $tahap === 'lulus' ? 'lulus' : 'aktif',
                    ]
                );

                // create tugas akhir & ujian
                $tugasAkhir = TugasAkhir::updateOrCreate(
                    ['mahasiswa_id' => $mahasiswa->id],
                    ['judul' => $judulTa, 'tahapan' => $tahap === 'lulus' ? 'skripsi' : $tahap]
                );

                if (in_array($tahap, ['hasil', 'skripsi', 'lulus'], true)) {
                    Ujian::updateOrCreate(
                        ['tugas_akhir_id' => $tugasAkhir->id, 'jenis_ujian' => 'proposal'],
                        [
                            'status' => 'selesai',
                            'periode_akademik_id' => $proposalPeriodeAktif === 'ya' ? $periodeAktifId : null,
                            'catatan' => null,
                        ]
                    );
                }

                if (in_array($tahap, ['skripsi', 'lulus'], true)) {
                    Ujian::updateOrCreate(
                        ['tugas_akhir_id' => $tugasAkhir->id, 'jenis_ujian' => 'hasil'],
                        [
                            'status' => 'selesai',
                            'periode_akademik_id' => null,
                            'catatan' => null,
                        ]
                    );
                }

                if ($tahap === 'lulus') {
                    Ujian::updateOrCreate(
                        ['tugas_akhir_id' => $tugasAkhir->id, 'jenis_ujian' => 'skripsi'],
                        [
                            'status' => 'selesai',
                            'periode_akademik_id' => null,
                            'catatan' => null,
                        ]
                    );
                }

                // create dosen pembimbing & penguji
                foreach ([
                    'pembimbing_1' => $pembimbing1Nidn,
                    'pembimbing_2' => $pembimbing2Nidn,
                ] as $jenisPembimbing => $nidn) {
                    $pembimbing = DosenPembimbing::firstOrNew([
                        'mahasiswa_id' => $mahasiswa->id,
                        'jenis_pembimbing' => $jenisPembimbing,
                    ]);
                    $pembimbing->dosen_id = $dosenByNidn[$nidn]->id;
                    $pembimbing->status_aktif = $tahap !== 'lulus';
                    $pembimbing->tanggal_mulai ??= now();
                    $pembimbing->tanggal_selesai = $tahap === 'lulus' ? now() : null;
                    $pembimbing->save();
                }

                if ($allPengujiFilled) {
                    foreach ([
                        'penguji_1' => $penguji1Nidn,
                        'penguji_2' => $penguji2Nidn,
                        'penguji_3' => $penguji3Nidn,
                    ] as $jenisPenguji => $nidn) {
                        DosenPenguji::updateOrCreate(
                            ['mahasiswa_id' => $mahasiswa->id, 'jenis_penguji' => $jenisPenguji],
                            ['dosen_id' => $dosenByNidn[$nidn]->id]
                        );
                    }
                }
            }
        });
    }

    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^E1E1(\d{2})\d+$/i', trim((string) $value)) !== 1) {
                        $fail('Format NIM tidak valid. Gunakan format seperti E1E122001.');
                    }
                },
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'peminatan' => ['nullable', Rule::in(['RPL', 'KCV', 'KBJ'])],
            'judul_ta' => ['required', 'string', 'max:255'],
            'tahap' => ['required', 'in:proposal,hasil,skripsi,lulus'],
            'pembimbing_1_nidn' => ['required', 'string', 'max:30'],
            'pembimbing_2_nidn' => ['required', 'string', 'max:30'],
            'penguji_1_nidn' => ['nullable', 'string', 'max:30'],
            'penguji_2_nidn' => ['nullable', 'string', 'max:30'],
            'penguji_3_nidn' => ['nullable', 'string', 'max:30'],
            'proposal_periode_aktif' => ['nullable', 'in:ya,tidak'],
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['nim'] = strtoupper(trim((string) ($data['nim'] ?? '')));
        $data['nama_lengkap'] = strtoupper(trim((string) ($data['nama_lengkap'] ?? '')));
        $peminatan = strtoupper(trim((string) ($data['peminatan'] ?? '')));
        $data['peminatan'] = $peminatan !== '' ? $peminatan : null;
        $data['judul_ta'] = trim((string) ($data['judul_ta'] ?? ''));
        $data['tahap'] = strtolower(trim((string) ($data['tahap'] ?? '')));
        $data['proposal_periode_aktif'] = strtolower(trim((string) ($data['proposal_periode_aktif'] ?? '')));
        $data['pembimbing_1_nidn'] = trim((string) ($data['pembimbing_1_nidn'] ?? ''));
        $data['pembimbing_2_nidn'] = trim((string) ($data['pembimbing_2_nidn'] ?? ''));
        $data['penguji_1_nidn'] = trim((string) ($data['penguji_1_nidn'] ?? ''));
        $data['penguji_2_nidn'] = trim((string) ($data['penguji_2_nidn'] ?? ''));
        $data['penguji_3_nidn'] = trim((string) ($data['penguji_3_nidn'] ?? ''));

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'peminatan.in' => 'Peminatan hanya boleh RPL, KCV, atau KBJ.',
            'judul_ta.required' => 'Judul tugas akhir wajib diisi.',
            'tahap.required' => 'Tahap wajib diisi.',
            'tahap.in' => 'Tahap hanya boleh proposal, hasil, skripsi, atau lulus.',
            'pembimbing_1_nidn.required' => 'Pembimbing 1 wajib diisi.',
            'pembimbing_2_nidn.required' => 'Pembimbing 2 wajib diisi.',
            'proposal_periode_aktif.in' => 'Proposal periode aktif hanya boleh ya atau tidak.',
        ];
    }
}
