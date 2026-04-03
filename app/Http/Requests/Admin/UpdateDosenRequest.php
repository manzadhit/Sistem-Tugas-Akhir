<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'keahlian' => ['required', 'string', 'max:255'],
            'jabatan_fungsional' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,cuti,nonaktif,pensiun'],
            'mata_kuliah_ids' => ['nullable', 'array'],
            'mata_kuliah_ids.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ];
    }
}
