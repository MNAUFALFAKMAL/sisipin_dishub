<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDisposisiRequest extends FormRequest
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
        $disposisi = $this->route('disposisi');

        // Hanya bisa update jika status pending (baru)
        if ($disposisi && $disposisi->status !== 'baru') {
            return []; // Tidak validasi apa pun, akan gagal di controller
        }

        return [
            'kepada_user_id'  => ['sometimes', 'exists:users,id'],
            'kepada_bidang_id'=> ['nullable', 'exists:bidangs,id'],
            'instruksi'       => ['sometimes', 'string', 'max:1000'],
            'prioritas'       => ['sometimes', Rule::in(['biasa', 'segera', 'mendesak'])],
            'sifat'           => ['sometimes', Rule::in(['biasa', 'rahasia', 'terbatas'])],
            'batas_waktu'     => ['sometimes', 'date', 'after_or_equal:today'],
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
            'kepada_user_id.exists'   => 'Penerima disposisi tidak valid.',
            'kepada_bidang_id.exists' => 'Bidang tujuan tidak valid.',
            'instruksi.max'           => 'Instruksi maksimal 1000 karakter.',
            'prioritas.in'            => 'Prioritas tidak valid. Pilih: biasa, segera, atau mendesak.',
            'sifat.in'                => 'Sifat disposisi tidak valid. Pilih: biasa, rahasia, atau terbatas.',
            'batas_waktu.date'        => 'Format tanggal tidak valid.',
            'batas_waktu.after_or_equal'=> 'Batas waktu tidak boleh sebelum hari ini.',
        ];
    }
}