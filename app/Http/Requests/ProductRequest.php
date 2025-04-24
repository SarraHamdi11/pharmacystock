<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'picture' => ['nullable', 'image', 'max:2048']
        ];
    }

   
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'description.required' => 'The product description is required.',
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price cannot be negative.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'The selected supplier is invalid.',
            'picture.image' => 'The file must be an image.',
            'picture.max' => 'The image size cannot exceed 2MB.'
        ];
    }
}