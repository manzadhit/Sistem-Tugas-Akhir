<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class ReviewSubmissionRequest extends FormRequest
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
            'status' => ['required', 'in:revisi,acc,reject'],
            'review' => ['nullable','string'],
            'files' => ['nullable','array'],
            'files.*' => ['file', 'mimes:pdf,doc,docx', 'max:10240']
        ];
    }
}
