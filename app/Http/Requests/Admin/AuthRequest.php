<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=> [
                'required',
                'min:3',
                'max:45',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'max:15',
            ],
        ];
    }

    public function messages(): array {
        return [
            'email.required' => 'Email or username is required.',
            'email.min' => 'Email must be at least :min characters.',
            'email.max' => 'Email must not exceed :max characters.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least :min characters.',
            'password.max' => 'Password must not exceed :max characters.',
        ];
    }
}
