<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitPengajuanUjianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->profileMahasiswa !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files'         => ['required', 'array', 'min:1'],
            'files.*'       => ['file', 'mimes:pdf', 'max:10240'],
            'tanggal_ujian' => ['required', 'date', 'after_or_equal:today'],
            'slot_waktu'    => ['required', Rule::in(['08:00-09:00', '09:30-11:00', '13:30-15:00', '15:00-16:30'])],
            'ruang_ujian'   => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required'               => 'Minimal satu dokumen harus diupload.',
            'files.*.mimes'                => 'Format file harus PDF',
            'files.*.max'                  => 'Ukuran file maksimal 10MB.',
            'tanggal_ujian.after_or_equal' => 'Tanggal ujian tidak boleh kurang dari hari ini.',
            'slot_waktu.in'                => 'Slot waktu yang dipilih tidak valid.',
        ];
    }
}
