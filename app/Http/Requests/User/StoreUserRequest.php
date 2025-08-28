<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $rules = [
            'name'          => 'required',
            'role'          => 'required',
            'email'         => 'required|email|unique:users,email,'.$this->id,
            'phone'         => 'required|numeric|digits:10|unique:users,phone,'.$this->id,
            'address'       => 'nullable|max:255',
        ];

        if (!$this->id) {
            $rules['password'] = 'required|min:8|max:15|same:confirm_password';
        }

        return $rules;
    }
}
