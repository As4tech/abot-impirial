<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KitchenPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|integer|exists:kitchen_ingredients,id',
            'items.*.quantity' => 'required|numeric|min:0.0001|max:999999.9999',
            'items.*.unit_cost' => 'required|numeric|min:0|max:999999.99',
            'items.*.notes' => 'nullable|string|max:500',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item must be added to the purchase.',
            'items.min' => 'At least one item must be added to the purchase.',
            'items.*.ingredient_id.required' => 'Ingredient is required for each item.',
            'items.*.ingredient_id.exists' => 'Selected ingredient does not exist.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.numeric' => 'Quantity must be a valid number.',
            'items.*.quantity.min' => 'Quantity must be greater than 0.',
            'items.*.quantity.max' => 'Quantity cannot exceed 999,999.9999.',
            'items.*.unit_cost.required' => 'Unit cost is required for each item.',
            'items.*.unit_cost.numeric' => 'Unit cost must be a valid number.',
            'items.*.unit_cost.min' => 'Unit cost cannot be negative.',
            'items.*.unit_cost.max' => 'Unit cost cannot exceed 999,999.99.',
            'items.*.notes.max' => 'Item notes must not exceed 500 characters.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
            'invoice_number.max' => 'Invoice number must not exceed 100 characters.',
            'purchase_date.required' => 'Purchase date is required.',
            'purchase_date.date' => 'Purchase date must be a valid date.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future.',
        ];
    }

    public function attributes(): array
    {
        return [
            'items' => 'purchase items',
            'items.*.ingredient_id' => 'ingredient',
            'items.*.quantity' => 'quantity',
            'items.*.unit_cost' => 'unit cost',
            'items.*.notes' => 'item notes',
            'supplier_id' => 'supplier',
            'invoice_number' => 'invoice number',
            'purchase_date' => 'purchase date',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean up numeric inputs for all items
        $items = $this->input('items', []);
        
        foreach ($items as $key => $item) {
            if (isset($item['quantity'])) {
                $items[$key]['quantity'] = (float) str_replace(',', '', $item['quantity']);
            }
            if (isset($item['unit_cost'])) {
                $items[$key]['unit_cost'] = (float) str_replace(',', '', $item['unit_cost']);
            }
        }

        $this->merge(['items' => $items]);
    }

    /**
     * Get custom validation logic for business rules
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate ingredients
            $items = $this->input('items', []);
            $ingredientIds = array_column($items, 'ingredient_id');
            
            if (count($ingredientIds) !== count(array_unique($ingredientIds))) {
                $validator->errors()->add('items', 'Each ingredient can only be added once per purchase.');
            }

            // Calculate total cost and validate against reasonable limits
            $totalCost = 0;
            foreach ($items as $item) {
                $totalCost += ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);
            }

            if ($totalCost > 9999999.99) {
                $validator->errors()->add('items', 'Total purchase cost exceeds maximum allowed amount.');
            }
        });
    }
}
