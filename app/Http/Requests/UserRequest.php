<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'email_address'     => 'required|string|email|max:255|unique:users,email_address',
            'phone_number'      => 'nullable|string|max:20', 
            'id_number'         => 'nullable|string|max:50|unique:users,id_number',
            'password'          => 'required|string|min:8|confirmed',
            'role'              => 'nullable|in:patient,admin',
        ];
    }
}
