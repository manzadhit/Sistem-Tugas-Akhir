<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SubmitHasilUjianRequest extends FormRequest
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
            'files' => 'required|array',
            'files.*' => 'file|mimetypes:application/pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Minimal satu dokumen harus diupload.',
            'files.*.mimes' => 'Format file harus PDF',
            'files.*.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
