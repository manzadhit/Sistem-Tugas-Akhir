<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'keahlian' => ['nullable', 'string', 'max:255'],
            'jabatan_fungsional' => ['required', 'string', 'max:100'],
            'mata_kuliah_ids' => ['nullable', 'array'],
            'mata_kuliah_ids.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'password' => ['nullable', 'string', Password::min(8)
                ->mixedCase()
                ->symbols(), 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'Nama Lengkap',
            'keahlian' => 'Keahlian',
            'jabatan_fungsional' => 'Jabatan Fungsional',
            'mata_kuliah_ids' => 'Mata Kuliah yang Diampu',
            'no_telp' => 'No. Telepon',
            'foto' => 'Foto Profil',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',

            'keahlian.max' => 'Keahlian maksimal 255 karakter.',

            'jabatan_fungsional.max' => 'Jabatan fungsional maksimal 100 karakter.',

            'no_telp.max' => 'No. telepon maksimal 20 karakter.',

            'foto.uploaded' => 'Ukuran foto terlalu besar. Maksimal yang diizinkan adalah 2MB.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.symbols' => 'Password harus mengandung minimal satu simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
