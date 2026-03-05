<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap'        => ['required', 'string', 'max:255'],
            'nidn'                => ['required', 'string', 'max:20'],
            'jurusan'             => ['required', 'string', 'max:100'],
            'program_studi'       => ['required', 'string', 'max:100'],
            'keahlian'            => ['nullable', 'string', 'max:255'],
            'jabatan_fungsional'  => ['nullable', 'string', 'max:100'],
            'no_telp'             => ['nullable', 'string', 'max:20'],
            'foto'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email'               => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'password'            => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap'       => 'Nama Lengkap',
            'nidn'               => 'NIDN',
            'jurusan'            => 'Jurusan',
            'program_studi'      => 'Program Studi',
            'keahlian'           => 'Keahlian',
            'jabatan_fungsional' => 'Jabatan Fungsional',
            'no_telp'            => 'No. Telepon',
            'foto'               => 'Foto Profil',
            'email'              => 'Email',
            'password'           => 'Password',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required'       => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'            => 'Nama lengkap maksimal 255 karakter.',

            'nidn.required'               => 'NIDN wajib diisi.',
            'nidn.max'                    => 'NIDN maksimal 20 karakter.',

            'jurusan.required'            => 'Jurusan wajib diisi.',
            'jurusan.max'                 => 'Jurusan maksimal 100 karakter.',

            'program_studi.required'      => 'Program studi wajib diisi.',
            'program_studi.max'           => 'Program studi maksimal 100 karakter.',

            'keahlian.max'                => 'Keahlian maksimal 255 karakter.',

            'jabatan_fungsional.max'      => 'Jabatan fungsional maksimal 100 karakter.',

            'no_telp.max'                 => 'No. telepon maksimal 20 karakter.',

            'foto.uploaded'               => 'Ukuran foto terlalu besar. Maksimal yang diizinkan adalah 2MB.',
            'foto.image'                  => 'File foto harus berupa gambar.',
            'foto.mimes'                  => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max'                    => 'Ukuran foto maksimal 2MB.',

            'email.required'              => 'Email wajib diisi.',
            'email.email'                 => 'Format email tidak valid.',
            'email.max'                   => 'Email maksimal 255 karakter.',
            'email.unique'                => 'Email sudah digunakan oleh akun lain.',

            'password.min'                => 'Password minimal 8 karakter.',
            'password.confirmed'          => 'Konfirmasi password tidak cocok.',
        ];
    }
}
