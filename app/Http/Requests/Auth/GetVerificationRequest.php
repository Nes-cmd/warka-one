<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GetVerificationRequest extends FormRequest
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
            // 'authwith' => 'required|in:phone,email',
            // 'phone' => ['nullable','required_if:authwith,phone', 'min:9', request()->get('otpIsFor') == 'registration' ? 'unique:users,phone' : 'exists:users,phone'],
            // 'email' => ['nullable','required_if:authwith,email', 'email', request()->get('otpIsFor') == 'registration' ? 'unique:users,email' : 'exists:users,email'],
            
            'phoneOrEmail' => 'required',
            'otpIsFor' => 'required|in:registration,reset-password,add-auth',
            'country_id' => ['nullable'],
        ];
    }
}
