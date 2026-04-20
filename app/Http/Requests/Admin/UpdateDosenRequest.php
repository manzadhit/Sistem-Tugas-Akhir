<?php

namespace App\Http\Requests\Admin;

use App\Models\ProfileDosen;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        // Get the user_id of the dosen being edited so we can exclude it from the uniqueness check
        $dosen = ProfileDosen::findOrFail($this->route('id'));
        $currentUserId = $dosen->user_id;

        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'role' => [
                'required',
                'in:dosen,kajur,sekjur',
                function (string $attribute, mixed $value, \Closure $fail) use ($currentUserId): void {
                    if (in_array($value, ['kajur', 'sekjur'], true)
                        && User::where('role', $value)->where('id', '!=', $currentUserId)->exists()
                    ) {
                        $label = strtoupper((string) $value);
                        $fail("Role {$label} sudah terisi. Hanya boleh ada 1 akun {$label}.");
                    }
                },
            ],
            'jurusan' => ['required', 'string', 'max:255'],
            'keahlian' => ['required', 'string', 'max:255'],
            'jabatan_fungsional' => ['required', 'string', 'max:255'],
            'sinta_score_3y' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'status' => ['required', 'in:aktif,cuti,nonaktif,pensiun'],
            'mata_kuliah_ids' => ['nullable', 'array'],
            'mata_kuliah_ids.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
