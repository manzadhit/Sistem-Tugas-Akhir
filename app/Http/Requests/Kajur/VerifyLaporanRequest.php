<?php

namespace App\Http\Requests\Kajur;

use Illuminate\Foundation\Http\FormRequest;

class VerifyLaporanRequest extends FormRequest
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
            'status' => ['required', 'in:pending,acc,revisi,reject'],
            'review' => ['nullable', 'string', 'required_if:status,revisi,reject'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:png,jpg,pdf,doc,docx', 'max:10240']
        ];
    }
}
