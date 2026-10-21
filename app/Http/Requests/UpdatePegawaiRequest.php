<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()?->role, ['super_admin', 'tata_usaha']);
    }

    public function rules(): array
    {
        return [
            'nama'        => ['required', 'string', 'max:150', Rule::unique('pegawais', 'nama')->ignore($this->route('pegawai'))],
            'nip'         => ['nullable', 'string', 'max:30'],
            'pangkat'     => ['nullable', 'string', 'max:150'],
            'golongan'    => ['nullable', 'string', 'max:20'],
            'jabatan'     => ['nullable', 'string', 'max:150'],
            'unit_bidang' => ['nullable', 'string', 'max:150'],
            'status'      => ['required', 'in:aktif,tidak_aktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'       => 'Nama pegawai wajib diisi.',
            'nama.unique'         => 'Nama pegawai sudah terdaftar.',
            'nip.max'             => 'NIP maksimal 30 karakter.',
            'status.required'     => 'Status pegawai wajib dipilih.',
            'status.in'           => 'Status pegawai tidak valid.',
        ];
    }
}
