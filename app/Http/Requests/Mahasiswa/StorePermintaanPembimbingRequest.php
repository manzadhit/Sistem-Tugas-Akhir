<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StorePermintaanPembimbingRequest extends FormRequest
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
            'ipk' => ['required', 'numeric', 'min:0', 'max:4'],
            'judul_ta' => ['required', 'string', 'max:500'],
            'bukti_acc' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:2048'],
            'mata_kuliah_ids' => ['required', 'array', 'min:1'],
            'mata_kuliah_ids.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
        ];
    }
}
