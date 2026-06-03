<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product') ?? null;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:45',
                Rule::unique('products', 'name')->ignore($id),
            ],

            'brand_id' => 'nullable',
            'category_id' => 'nullable',
            'price' => 'required|numeric|min:10|max:100000000',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.min' => 'Product name must be at least 3 characters.',
            'name.max' => 'Product name may not exceed 45 characters.',
            'name.unique' => 'Product name already exists.',

            'brand_id.exists' => 'Brand does not exist.',
            'category_id.exists' => 'Category does not exist.',

            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 10.',
            'price.max' => 'Price must not exceed 100,000,000.',

            'discount_percent.integer' => 'Discount must be a number.',
            'discount_percent.min' => 'Discount cannot be less than 0%.',
            'discount_percent.max' => 'Discount cannot exceed 100%.',

            'description.max' => 'Description may not exceed 2000 characters.',

            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpeg, png, jpg, gif, or webp.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}

//Imgea mút be a jpeg, png 
