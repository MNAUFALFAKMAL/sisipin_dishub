<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProsesDisposisiRequest extends FormRequest
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

        return [
            'catatan_hasil'   => ['required', 'string', 'max:1000'],
            'status_disposisi'=> ['required', Rule::in(['diproses', 'selesai', 'ditolak'])],
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
            'catatan_hasil.required'   => 'Catatan hasil disposisi wajib diisi.',
            'status_disposisi.required'=> 'Status disposisi wajib dipilih.',
            'status_disposisi.in'      => 'Status disposisi tidak valid. Pilih: diproses, selesai, atau ditolak.',
        ];
    }
}