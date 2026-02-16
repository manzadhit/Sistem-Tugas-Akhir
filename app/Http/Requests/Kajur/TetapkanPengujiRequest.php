<?php

namespace App\Http\Requests\Kajur;

use Illuminate\Foundation\Http\FormRequest;

class TetapkanPengujiRequest extends FormRequest
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
        return
            [
                'penguji_ids' => ['required', 'array', 'min:3', 'max:3',],
                'penguji_ids.*' => ['integer', 'distinct', 'exists:profile_dosen,id']
            ];
    }
}
