<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'unique:profile_mahasiswa,nim', 'unique:users,username'],
            'angkatan' => ['required', 'digits:4', 'integer', 'min:2000', 'max:'.date('Y')],
            'jurusan' => ['required', 'string', 'max:255'],
            'peminatan' => ['nullable', Rule::in(['RPL', 'KCV', 'KBJ'])],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'status_akademik' => ['required', 'in:aktif,cuti,nonaktif,lulus,dropout'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar.',
            'peminatan.in' => 'Peminatan tidak valid.',
        ];
    }
}
