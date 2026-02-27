<?php

namespace App\Http\Requests\Admin;

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
      'nama_lengkap' => ['required', 'string', 'max:255'],
      'jurusan' => ['required', 'string', 'max:255'],
      'program_studi' => ['required', 'string', 'max:255'],
      'keahlian' => ['required', 'string', 'max:255'],
      'jabatan_fungsional' => ['required', 'string', 'max:255'],
      'status' => ['required', 'in:aktif,cuti,nonaktif,pensiun'],
      'no_telp' => ['nullable', 'string', 'max:20'],
    ];
  }

  public function messages(): array
  {
    return [
      'nidn.unique' => 'NIDN sudah terdaftar.',
    ];
  }
}
