<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ProfileMahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
};

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private string $defaultPasswordHash;

    public function __construct()
    {
        $this->defaultPasswordHash = Hash::make('12345@#');
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $nim = strtoupper(trim($row['nim']));
                $namaLengkap = strtoupper(trim($row['nama_lengkap']));

                // Ambil angkatan dari digit ke-5 & ke-6 NIM (E1E1YY...)
                $angkatan = preg_match('/^E1E1(\d{2})/i', $nim, $m) ? 2000 + (int) $m[1] : null;

                $user = User::firstOrCreate(
                    ['username' => $nim],
                    [
                        'password'             => $this->defaultPasswordHash,
                        'role'                 => 'mahasiswa',
                        'must_change_password'  => true,
                    ]
                );

                ProfileMahasiswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim'              => $nim,
                        'nama_lengkap'     => $namaLengkap,
                        'jurusan'          => 'Informatika',
                        'angkatan'         => $angkatan,
                        'status_akademik'  => 'aktif',
                    ]
                );
            }
        });
    }

    public function rules(): array
    {
        return [
            'nim'           => 'required|string|max:20',
            'nama_lengkap'  => 'required|string|max:255',
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['nim']          = (string) ($data['nim'] ?? '');
        $data['nama_lengkap'] = (string) ($data['nama_lengkap'] ?? '');

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            'nim.required'          => 'NIM wajib diisi',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
        ];
    }
}
