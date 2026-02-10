<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
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
            'pembimbing' => ['required', 'integer'],
            'file_submission' => ['required', 'array', 'min:1'],
            'file_submission.*' => ['file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }
}
