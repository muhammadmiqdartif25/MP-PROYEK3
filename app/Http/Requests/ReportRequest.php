<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'incident_at' => ['required', 'date'],
            'area_id' => ['required', 'exists:areas,id'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'description' => 'deskripsi',
            'incident_at' => 'waktu kejadian',
            'area_id' => 'wilayah',
            'address' => 'alamat',
            'latitude' => 'lintang',
            'longitude' => 'bujur',
            'evidence' => 'bukti',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'between' => ':attribute harus di antara :min dan :max.',
            'file' => ':attribute harus berupa berkas.',
            'mimes' => ':attribute harus berformat jpg, jpeg, png, atau pdf.',
            'evidence.max' => 'Ukuran bukti maksimal 2 MB.',
        ];
    }
}
