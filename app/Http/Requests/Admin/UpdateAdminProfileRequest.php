<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', Password::min(8)
                ->mixedCase()
                ->symbols(), 'confirmed'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('current_password', ['required', 'current_password'], function (): bool {
            $user = $this->user();

            return $user !== null
                && (
                    (string) $this->input('email') !== (string) $user->email
                    || $this->filled('password')
                );
        });
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'current_password' => 'Password saat ini',
            'password' => 'Password baru',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'current_password.required' => 'Password saat ini wajib diisi untuk menyimpan perubahan.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.mixed' => 'Password baru harus mengandung huruf besar dan huruf kecil.',
            'password.symbols' => 'Password baru harus mengandung minimal satu simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
