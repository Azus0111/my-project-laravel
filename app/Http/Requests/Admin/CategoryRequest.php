<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category') ?? null;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:45',
                Rule::unique('categories', 'name')->ignore($id),
            ],

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.string' => 'Category name must be a string.',
            'name.min' => 'Category name must be at least 3 characters.',
            'name.max' => 'Category name may not exceed 45 characters.',
            'name.unique' => 'Category name already exists.',

            'image.image' => 'Uploaded file must be an image.',
            'image.max' => 'Image size must be less than 2MB.',
            'image.mimes' => 'Image must be a jpeg, png, jpg, gif, or webp file.',
        ];
    }
}
