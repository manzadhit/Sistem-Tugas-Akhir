<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreKajurSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'catatan' => ['nullable', 'string'],
            'abstrak' => ['required', 'string', 'min:50'],
            'kata_kunci' => [
                'required', 'string',
                function ($attribute, $value, $fail) {
                    $keywords = array_filter(array_map('trim', explode(',', $value)));
                    if (count($keywords) < 5) {
                        $fail('Kata kunci wajib diisi minimal 5.');
                    }
                },
            ],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,doc,docx', 'max:10240']
        ];
    }

    public function messages(): array
    {
        return [
            'abstrak.required' => 'Abstrak tugas akhir wajib diisi.',
            'abstrak.min' => 'Abstrak terlalu singkat, minimal 50 karakter.',
            'kata_kunci.required' => 'Kata kunci wajib diisi, minimal 5 kata kunci.',
            'files.required' => 'File laporan wajib diupload.',
            'files.min' => 'Upload minimal 1 file laporan.',
            'files.*.mimes' => 'Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.',
            'files.*.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
