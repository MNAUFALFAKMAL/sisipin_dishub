<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDisposisiRequest extends FormRequest
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
            'surat_masuk_id'  => ['required', 'exists:surat_masuks,id'],
            'kepada_user_id'  => ['required', 'exists:users,id'],
            'kepada_bidang_id'=> ['nullable', 'exists:bidangs,id'],
            'instruksi'       => ['required', 'string', 'max:1000'],
            'prioritas'       => ['required', Rule::in(['biasa', 'segera', 'mendesak'])],
            'sifat'           => ['required', Rule::in(['biasa', 'rahasia', 'terbatas'])],
            'batas_waktu'     => ['required', 'date', 'after_or_equal:today'],
            'catatan'         => ['nullable', 'string', 'max:1000'],
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
            'surat_masuk_id.required'   => 'Surat masuk wajib dipilih.',
            'surat_masuk_id.exists'     => 'Surat masuk tidak valid.',
            'kepada_user_id.required'   => 'Penerima disposisi wajib dipilih.',
            'kepada_user_id.exists'     => 'Penerima disposisi tidak valid.',
            'kepada_bidang_id.exists'   => 'Bidang tujuan tidak valid.',
            'instruksi.required'        => 'Instruksi disposisi wajib diisi.',
            'prioritas.required'        => 'Prioritas wajib dipilih.',
            'prioritas.in'              => 'Prioritas tidak valid. Pilih: biasa, segera, atau mendesak.',
            'sifat.required'            => 'Sifat disposisi wajib dipilih.',
            'sifat.in'                  => 'Sifat disposisi tidak valid. Pilih: biasa, rahasia, atau terbatas.',
            'batas_waktu.required'      => 'Batas waktu wajib diisi.',
            'batas_waktu.date'          => 'Format tanggal tidak valid.',
            'batas_waktu.after_or_equal'=> 'Batas waktu tidak boleh sebelum hari ini.',
        ];
    }
}