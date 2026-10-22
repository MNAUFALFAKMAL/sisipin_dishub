<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuratKeluarRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'jenis_surat'     => ['required', 'string', Rule::in(['SPT', 'SPPD', 'SK', 'Undangan'])],
            'no_agenda'       => ['required', 'string', 'max:50', Rule::unique('surat_keluars')->where('tahun', $this->tahun)],
            'tahun'           => ['required', 'integer', 'min:2020', 'max:2030', 'exists:tahun_arsips,tahun'],
            'no_surat'        => ['required', 'string', 'max:100'],
            'tanggal_surat'   => ['required', 'date', 'before_or_equal:today'],
            'tanggal_terima'  => ['required', 'date', 'before_or_equal:today'],
            'tujuan'          => ['required', 'string', 'max:500'],
            'perihal'         => ['required', 'string', 'max:255'],
            'isi_ringkas'     => ['nullable', 'string', 'max:1000'],
            'klasifikasi_id'  => ['required', 'exists:klasifikasi_arsips,id'],
            'bidang_id'       => ['required', 'exists:bidangs,id'],
            'penandatangan'   => ['required', 'string', 'max:150'],
            'file_surat'      => ['nullable', 'array', 'max:5'],
            'file_surat.*'    => ['file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
            'indeks_berkas'   => ['nullable', 'string', 'max:100'],
            'tingkat_keamanan'=> ['nullable', 'string', 'max:50', Rule::in(['Biasa', 'Rahasia', 'Sangat Rahasia', 'Terbatas'])],
            'masa_simpan'     => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis_surat.required'    => 'Jenis surat wajib dipilih.',
            'jenis_surat.in'          => 'Jenis surat tidak valid. Pilih: SPT, SPPD, SK, atau Undangan.',
            'no_agenda.required'      => 'Nomor agenda wajib diisi.',
            'no_agenda.unique'        => 'Nomor agenda sudah digunakan untuk tahun ini.',
            'tahun.required'          => 'Tahun wajib diisi.',
            'tahun.exists'            => 'Tahun tidak valid atau belum terdaftar di master tahun arsip.',
            'no_surat.required'       => 'Nomor surat wajib diisi.',
            'tanggal_surat.required'  => 'Tanggal surat wajib diisi.',
            'tanggal_surat.before_or_equal' => 'Tanggal surat tidak boleh melebihi hari ini.',
            'tanggal_terima.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_terima.before_or_equal' => 'Tanggal keluar tidak boleh melebihi hari ini.',
            'tujuan.required'         => 'Tujuan surat wajib diisi.',
            'perihal.required'        => 'Perihal wajib diisi.',
            'klasifikasi_id.required' => 'Klasifikasi arsip wajib dipilih.',
            'klasifikasi_id.exists'   => 'Klasifikasi arsip tidak valid.',
            'bidang_id.required'      => 'Bidang wajib dipilih.',
            'bidang_id.exists'        => 'Bidang tidak valid.',
            'penandatangan.required'  => 'Penandatangan wajib diisi.',
            'file_surat.array'        => 'Format file tidak valid.',
            'file_surat.max'          => 'Maksimal 5 file.',
            'file_surat.*.file'       => 'File tidak valid.',
            'file_surat.*.max'        => 'Ukuran file maksimal 5 MB.',
            'file_surat.*.mimes'      => 'Format file harus PDF, JPG, PNG, DOC, atau DOCX.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->isi_ringkas === '') {
            $this->merge(['isi_ringkas' => null]);
        }
        if ($this->keterangan === '') {
            $this->merge(['keterangan' => null]);
        }
    }
}