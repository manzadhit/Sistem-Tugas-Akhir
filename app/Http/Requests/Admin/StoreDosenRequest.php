<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nidn' => ['required', 'string', 'max:20', 'unique:profile_dosen,nidn', 'unique:users,username'],
            'role' => [
                'required',
                'in:dosen,kajur,sekjur',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (in_array($value, ['kajur', 'sekjur'], true) && User::where('role', $value)->exists()) {
                        $label = strtoupper((string) $value);
                        $fail("Role {$label} sudah terisi. Hanya boleh ada 1 akun {$label}.");
                    }
                },
            ],
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

    public function messages(): array
    {
        return [
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
