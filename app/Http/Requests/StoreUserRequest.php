<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:150'],
            'username'  => ['required', 'string', 'max:100', 'unique:users,email'],
            'password'  => ['required', Password::min(8)->letters()->numbers()],
            'role'      => ['required', Role::validationRules()],
            'bidang_id' => ['nullable', 'exists:bidangs,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan oleh pengguna lain.',
            'password.required'  => 'Password wajib diisi.',
            'role.required'      => 'Role pengguna wajib dipilih.',
            'role.in'            => 'Role tidak valid.',
            'bidang_id.exists'   => 'Bidang yang dipilih tidak ditemukan.',
        ];
    }
}
