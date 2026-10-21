<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:100', Rule::unique('users', 'email')->ignore($userId)],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
            'peran_id'   => ['required', 'exists:perans,id'],
            'bidang_id'  => ['nullable', 'exists:bidangs,id'],
            'is_active'  => ['boolean'],
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
            'name.required'       => 'Nama wajib diisi.',
            'username.required'   => 'Username/Email wajib diisi.',
            'username.unique'     => 'Username/Email sudah terdaftar.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'peran_id.required'   => 'Peran wajib dipilih.',
            'peran_id.exists'     => 'Peran tidak valid.',
            'bidang_id.exists'    => 'Bidang tidak valid.',
        ];
    }
}