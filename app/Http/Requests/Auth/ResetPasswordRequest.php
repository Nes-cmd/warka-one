<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            // 'authwith' => ['required', 'in:phone,email'],
            // 'email'    => ['nullable','required_if:authwith,email', 'string', 'email'],
            // 'phone'    => ['nullable','required_if:authwith,phone', 'min:9'],
            
            'phoneOrEmail' => 'required',
            'country_id' => ['nullable'],
            'password' => ['required', 'confirmed', 'min:6'],
            'password_confirmation' => ['required'],
        ];
    }
}
