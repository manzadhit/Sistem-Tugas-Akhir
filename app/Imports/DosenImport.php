<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ProfileDosen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
};

class DosenImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
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
                $user = User::firstOrCreate(
                    ['username' => trim($row['nidn'])],
                    [
                        'password'             => $this->defaultPasswordHash,
                        'role'                 => 'dosen',
                        'must_change_password'  => true,
                    ]
                );

                $email = trim($row['email'] ?? '');
                if ($email) {
                    $user->update(['email' => $email]);
                }

                ProfileDosen::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nidn'              => trim($row['nidn']),
                        'nama_lengkap'      => trim($row['nama_lengkap']),
                        'jurusan'           => trim($row['jurusan'] ?? ''),
                        'jabatan_fungsional' => trim($row['jabatan_fungsional'] ?? ''),
                        'rumpun_ilmu'        => trim($row['rumpun_ilmu'] ?? ''),
                        'no_telp'           => trim($row['no_telp'] ?? ''),
                        'status'            => 'aktif',
                    ]
                );
            }
        });
    }

    public function rules(): array
    {
        return [
            'nidn'               => 'required|string|max:20',
            'nama_lengkap'       => 'required|string|max:255',
            'jurusan'            => 'required|string|max:255',
            'jabatan_fungsional' => 'required|string|max:255',
            'rumpun_ilmu'        => 'nullable|string|max:255',
            'email'              => 'nullable|email|max:255',
            'no_telp'            => 'nullable|string|max:20',
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['nidn']              = (string) ($data['nidn'] ?? '');
        $data['nama_lengkap']      = (string) ($data['nama_lengkap'] ?? '');
        $data['email']             = (string) ($data['email'] ?? '');
        $data['jurusan']           = (string) ($data['jurusan'] ?? '');
        $data['jabatan_fungsional'] = (string) ($data['jabatan_fungsional'] ?? '');
        $data['rumpun_ilmu']        = (string) ($data['rumpun_ilmu'] ?? '');
        $data['no_telp']           = (string) ($data['no_telp'] ?? '');

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            'nidn.required'          => 'NIDN wajib diisi',
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi',
            'jurusan.required'       => 'Jurusan wajib diisi',
            'email.email'            => 'Format email tidak valid',
            'no_telp.max'            => 'No. telepon maksimal 20 karakter',
        ];
    }
}
