<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratMasukRequest extends FormRequest
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
        $suratMasuk = $this->route('surat_masuk');

        return [
            'no_agenda'       => ['required', 'string', 'max:50', Rule::unique('surat_masuks')->where('tahun', $this->tahun)->ignore($suratMasuk->id)],
            'tahun'           => ['required', 'integer', 'min:2020', 'max:2030', 'exists:tahun_arsips,tahun'],
            'jenis_surat'     => ['required', 'string', 'max:50', Rule::in(['Surat Masuk', 'Undangan Masuk'])],
            'no_surat'        => ['required', 'string', 'max:100'],
            'tanggal_surat'   => ['required', 'date', 'before_or_equal:today'],
            'tanggal_terima'  => ['required', 'date', 'before_or_equal:today'],
            'pengirim'        => ['required', 'string', 'max:150'],
            'perihal'         => ['required', 'string', 'max:255'],
            'isi_ringkas'     => ['nullable', 'string', 'max:1000'],
            'klasifikasi_id'  => ['required', 'exists:klasifikasi_arsips,id'],
            'bidang_tujuan_id'=> ['required', 'exists:bidangs,id'],
            'file_surat'      => ['nullable', 'array', 'max:5'],
            'file_surat.*'    => ['file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'hapus_file'      => ['nullable', 'array'],
            'hapus_file.*'    => ['string'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
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
            'no_agenda.required'        => 'Nomor agenda wajib diisi.',
            'no_agenda.unique'          => 'Nomor agenda sudah digunakan untuk tahun ini.',
            'tahun.required'            => 'Tahun wajib diisi.',
            'tahun.exists'              => 'Tahun tidak valid atau belum terdaftar di master tahun arsip.',
            'jenis_surat.required'      => 'Jenis surat wajib dipilih.',
            'jenis_surat.in'            => 'Jenis surat tidak valid.',
            'no_surat.required'         => 'Nomor surat wajib diisi.',
            'tanggal_surat.required'    => 'Tanggal surat wajib diisi.',
            'tanggal_surat.before_or_equal' => 'Tanggal surat tidak boleh melebihi hari ini.',
            'tanggal_terima.required'   => 'Tanggal terima wajib diisi.',
            'tanggal_terima.before_or_equal' => 'Tanggal terima tidak boleh melebihi hari ini.',
            'pengirim.required'         => 'Pengirim/Asal surat wajib diisi.',
            'perihal.required'          => 'Perihal wajib diisi.',
            'klasifikasi_id.required'   => 'Klasifikasi arsip wajib dipilih.',
            'klasifikasi_id.exists'     => 'Klasifikasi arsip tidak valid.',
            'bidang_tujuan_id.required' => 'Bidang tujuan wajib dipilih.',
            'bidang_tujuan_id.exists'   => 'Bidang tujuan tidak valid.',
            'file_surat.array'          => 'Format file tidak valid.',
            'file_surat.max'            => 'Maksimal 5 file.',
            'file_surat.*.file'         => 'File tidak valid.',
            'file_surat.*.max'          => 'Ukuran file maksimal 5 MB.',
            'file_surat.*.mimes'        => 'Format file harus PDF, JPG, PNG, DOC, atau DOCX.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for nullable fields
        if ($this->isi_ringkas === '') {
            $this->merge(['isi_ringkas' => null]);
        }
        if ($this->keterangan === '') {
            $this->merge(['keterangan' => null]);
        }
    }
}