<?php

namespace App\Http\Requests\Mahasiswa;

use App\Models\PermintaanPembimbing;
use App\Models\TugasAkhir;
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
        $mahasiswaId = $this->user()->profileMahasiswa?->id;

        return [
            'ipk' => ['required', 'numeric', 'min:0', 'max:4'],
            'judul_ta' => [
                'required', 'string', 'max:500',
                function (string $attribute, mixed $value, \Closure $fail) use ($mahasiswaId) {
                    // Cek duplikat di tabel tugas_akhir (judul yang sudah ditetapkan)
                    $existsInTugasAkhir = TugasAkhir::where('judul', $value)
                        ->where('mahasiswa_id', '!=', $mahasiswaId)
                        ->exists();

                    if ($existsInTugasAkhir) {
                        $fail('Judul tugas akhir ini sudah digunakan oleh mahasiswa lain.');
                        return;
                    }

                    // Cek duplikat di tabel permintaan_pembimbing (permintaan yang masih pending)
                    $existsInPermintaan = PermintaanPembimbing::where('judul_ta', $value)
                        ->where('mahasiswa_id', '!=', $mahasiswaId)
                        ->where('status', 'pending')
                        ->exists();

                    if ($existsInPermintaan) {
                        $fail('Judul tugas akhir ini sudah diajukan oleh mahasiswa lain.');
                    }
                },
            ],
            'bukti_acc' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:2048'],
            'mata_kuliah_ids' => ['required', 'array', 'min:1'],
            'mata_kuliah_ids.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
        ];
    }
}
