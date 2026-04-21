<?php

namespace App\Imports;

use App\Models\PublikasiDosen;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PublikasiImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function __construct(
        private readonly int $dosenId,
    ) {
    }

    public function model(array $row)
    {
        $judul = trim((string) ($row['title'] ?? ''));
        $jenisPublikasi = strtolower(trim((string) ($row['jenis_publikasi'] ?? '')));
        $url = trim((string) ($row['url'] ?? ''));
        $abstrak = trim((string) ($row['abstrak'] ?? ''));

        return PublikasiDosen::updateOrCreate(
            [
                'dosen_id' => $this->dosenId,
                'judul' => $judul,
                'jenis_publikasi' => $jenisPublikasi,
                'tahun' => (int) $row['tahun'],
            ],
            [
                'abstrak' => $abstrak !== '' ? $abstrak : null,
                'penerbit' => null,
                'url' => $url !== '' ? $url : null,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'title' => 'required|string|max:255',
            'jenis_publikasi' => 'required|in:jurnal,haki,buku',
            'url' => 'nullable|url:http,https|max:255',
            'abstrak' => 'nullable|string',
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['tahun'] = (string) ($data['tahun'] ?? '');
        $data['title'] = (string) ($data['title'] ?? ($data['judul'] ?? ''));
        $data['jenis_publikasi'] = strtolower(trim((string) ($data['jenis_publikasi'] ?? '')));
        $data['url'] = (string) ($data['url'] ?? '');
        $data['abstrak'] = (string) ($data['abstrak'] ?? '');

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            'tahun.required' => 'Kolom tahun wajib diisi.',
            'tahun.integer' => 'Kolom tahun harus berupa angka.',
            'title.required' => 'Kolom title wajib diisi.',
            'jenis_publikasi.required' => 'Kolom jenis_publikasi wajib diisi.',
            'jenis_publikasi.in' => 'Kolom jenis_publikasi harus berisi jurnal, buku, atau haki.',
            'url.url' => 'Kolom url harus berupa tautan yang valid.',
        ];
    }
}
