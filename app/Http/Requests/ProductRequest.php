<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return $this->user()->can('manage products');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'track_expiry' => $this->has('track_expiry'),
        ]);
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
            'generic_name' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'code_bar' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'track_expiry' => ['boolean'],
            'description' => ['nullable', 'string'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ];
    }

   
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price cannot be negative.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'The selected supplier is invalid.',
            'min_stock.required' => 'The minimum stock is required.',
            'min_stock.integer' => 'The minimum stock must be a number.',
            'min_stock.min' => 'The minimum stock cannot be negative.',
            'picture.image' => 'The file must be an image.',
            'picture.max' => 'The image size cannot exceed 2MB.'
        ];
    }
}