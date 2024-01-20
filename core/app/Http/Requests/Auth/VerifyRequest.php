<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
            'verificationCode' => ['required','numeric', 'digits:4'],
            'authwith' => ['required', 'in:phone,email'],
            'email'    => ['required_if:authwith,email', 'string', 'email'],
            'phone'    => ['required_if:authwith,phone', 'size:9'],
            'country_id' => ['required_if:authwith,phone'],
        ];
    }
}
