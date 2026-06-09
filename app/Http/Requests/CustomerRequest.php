<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return $this->user()->can('manage patients');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ];
        
        // Add the unique rule with customer ID exception for updates
        if ($this->customer) {
            $rules['email'] .= '|unique:customers,email,' . $this->customer->id;
        } else {
            $rules['email'] .= '|unique:customers,email';
        }
        
        return $rules;
    }
}