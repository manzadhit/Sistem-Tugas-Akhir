<?php

namespace App\Imports;

use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
};

class MataKuliahImport implements ToModel, WithHeadingRow, WithValidation
, SkipsEmptyRows{
    public function model(array $row)
    {
        return MataKuliah::updateOrCreate(
            ['kode' => strtoupper(trim($row['kode']))],
            ['nama' => trim($row['nama'])]
        );
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
        ];
    }

    public function prepareForValidation(array $data)
    {
        $data['kode'] = (string) $data['kode'];
        $data['nama'] = (string) $data['nama'];

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            'kode.required' => 'Kode wajib diisi',
            'nama.required' => 'Nama mata kuliah wajib diisi',
        ];
    }
}
