<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodeAkademikRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->role === 'admin';
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tahun_ajaran' => trim($this->input('tahun_ajaran', '')),
            'semester'     => strtolower($this->input('semester', '')),
        ]);
    }

    public function rules()
    {
        $periode = $this->route('periodeAkademik');

        $tanggalRules = [
            'mulai_at'   => ['required', 'date'],
            'selesai_at' => ['nullable', 'date', 'after_or_equal:mulai_at'],
        ];

        // Aktif & Selesai → hanya tanggal yang boleh dikoreksi
        if (! $periode->isDraft()) {
            return $tanggalRules;
        }

        // Draft → semua field boleh diubah
        return [
            'tahun_ajaran' => [
                'required',
                'string',
                'max:9',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('periode_akademik', 'tahun_ajaran')
                    ->ignore($periode->id)
                    ->where(fn($q) => $q->where('semester', $this->input('semester'))),
            ],
            'semester'  => ['required', 'in:ganjil,genap'],
            ...$tanggalRules,
        ];
    }

    public function messages()
    {
        return [
            'tahun_ajaran.required'     => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex'        => 'Format tahun ajaran harus seperti 2024/2025.',
            'tahun_ajaran.unique'       => 'Periode untuk tahun ajaran dan semester ini sudah ada.',
            'semester.required'         => 'Semester wajib dipilih.',
            'mulai_at.required'         => 'Tanggal mulai wajib diisi.',
            'selesai_at.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ];
    }
}
