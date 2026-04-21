<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'ipk' => ['nullable', 'numeric', 'min:0', 'max:4'],
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
            'ipk' => 'IPK',
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

            'ipk.numeric' => 'IPK harus berupa angka.',
            'ipk.min' => 'IPK minimal 0.',
            'ipk.max' => 'IPK maksimal 4.',

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
