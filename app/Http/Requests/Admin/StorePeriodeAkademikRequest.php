<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriodeAkademikRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->role === 'admin';
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tahun_ajaran' => trim((string) $this->input('tahun_ajaran')),
            'semester' => strtolower((string) $this->input('semester')),
        ]);
    }

    public function rules()
    {
        return [
            'tahun_ajaran' => [
                'required',
                'string',
                'max:9',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('periode_akademik', 'tahun_ajaran')
                    ->where(fn ($query) => $query->where('semester', $this->input('semester'))),
            ],
            'semester' => ['required', 'in:ganjil,genap'],
            'mulai_at' => ['required', 'date'],
            'selesai_at' => ['nullable', 'date', 'after_or_equal:mulai_at'],
        ];
    }

    public function messages()
    {
        return [
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun_ajaran.unique' => 'Periode akademik untuk tahun ajaran dan semester tersebut sudah ada.',
            'selesai_at.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}
