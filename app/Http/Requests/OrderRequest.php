<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage orders');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return [
                'customer_id' => 'required|exists:customers,id',
                'order_date' => 'nullable|date',
                'status' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ];
        }

        return [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'order_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string',
            'total_amount' => 'sometimes|required|numeric|min:0',
        ];
    }
}
