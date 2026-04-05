<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitchenStockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adjustment_type' => [
                'required',
                'string',
                Rule::in(['purchase', 'waste', 'adjustment'])
            ],
            'quantity' => [
                'required',
                'numeric',
                'min:0.0001',
                'max:999999.9999'
            ],
            'unit_cost' => [
                'required_if:adjustment_type,purchase',
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'adjustment_type.required' => 'Adjustment type is required.',
            'adjustment_type.in' => 'Invalid adjustment type selected.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be greater than 0.',
            'quantity.max' => 'Quantity cannot exceed 999,999.9999.',
            'unit_cost.required_if' => 'Unit cost is required for purchase adjustments.',
            'unit_cost.numeric' => 'Unit cost must be a valid number.',
            'unit_cost.min' => 'Unit cost cannot be negative.',
            'unit_cost.max' => 'Unit cost cannot exceed 999,999.99.',
            'notes.max' => 'Notes must not exceed 500 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'adjustment_type' => 'adjustment type',
            'quantity' => 'quantity',
            'unit_cost' => 'unit cost',
            'notes' => 'notes',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean up numeric inputs
        if ($this->has('quantity')) {
            $this->merge([
                'quantity' => (float) str_replace(',', '', $this->input('quantity')),
            ]);
        }

        if ($this->has('unit_cost')) {
            $this->merge([
                'unit_cost' => (float) str_replace(',', '', $this->input('unit_cost')),
            ]);
        }

        // Normalize adjustment type
        if ($this->has('adjustment_type')) {
            $this->merge([
                'adjustment_type' => strtolower(trim($this->input('adjustment_type'))),
            ]);
        }
    }

    /**
     * Get custom validation logic for business rules
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional business validation can be added here
            // For example, check if user has permission for certain adjustment types
        });
    }
}
