<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('brand') ?? null;
        return [
            'name' => 'required|string|min:3|max:45|unique:brands,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Brand name is required.',
            'name.unique' => 'Brand name already exists.',
            'name.min' => 'Brand name must be at least 3 characters.',
            'image.image' => 'Uploaded file must be an image.',
            'image.max' => 'Image size must be less than 2MB.',
            'image.mines' => 'Image must be a jpeg, png, jpg, gif, or webp file.',
        ];
    }
}
