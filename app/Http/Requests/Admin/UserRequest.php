<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user') ?? null;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('users', 'name')->ignore($id),
            ],

            'fullname' => [
                'required',
                'string',
                'min:3',
                'max:30',
            ],

            'email' => [
                'required',
                'email',
                'min:5',
                'max:45',
                Rule::unique('users', 'email')->ignore($id),
            ],

            'password' => $id ? [
                'nullable',
                'string',
                'min:6',
                'max:15',
                'confirmed',
            ] : [
                'required',
                'string',
                'min:6',
                'max:15',
                'confirmed',
            ],

            'role' => [
                'boolean',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'name.string' => 'User name must be a valid string.',
            'name.min' => 'User name must be at least 3 characters long.',
            'name.max' => 'User name may not be longer than 30 characters.',
            'name.regex' => 'User name can only contain letters, numbers, and underscores.',
            'name.unique' => 'User name already exists.',

            'fullname.required' => 'Full name is required.',
            'fullname.string' => 'Full name must be a valid string.',
            'fullname.min' => 'Full name must be at least 3 characters long.',
            'fullname.max' => 'Full name may not be longer than 30 characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.min' => 'Email address must be at least 5 characters long.',
            'email.max' => 'Email address may not be longer than 45 characters.',
            'email.unique' => 'This email address is already in use.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.max' => 'Password may not be longer than 15 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'role.boolean' => 'Role must be a valid boolean value (true or false).',

            'avatar.image' => 'Avatar must be an image.',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Avatar size may not exceed 2MB.',
        ];
    }
}
