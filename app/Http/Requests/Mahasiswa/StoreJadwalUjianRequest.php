<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJadwalUjianRequest extends FormRequest
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
        $jenisRoute = (string) $this->route('jenis');

        return [
            'tanggal_ujian' => ['required', 'date', 'after_or_equal:today'],
            'jenis_ujian' => ['required', Rule::in([$jenisRoute])],
            'slot_waktu' => ['required', Rule::in(['08:00-09:00', '09:30-11:00', '13:30-15:00', '15:00-16:30'])],
            'ruang_ujian' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_ujian.after_or_equal' => 'Tanggal ujian tidak boleh kurang dari hari ini.',
            'jenis_ujian.in' => 'Jenis ujian tidak sesuai dengan halaman yang sedang diakses.',
            'slot_waktu.in' => 'Slot waktu yang dipilih tidak valid.',
        ];
    }
}
