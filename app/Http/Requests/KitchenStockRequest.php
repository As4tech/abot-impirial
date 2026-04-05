<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitchenStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $ingredientId = $this->route('kitchenStock')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $isUpdate 
                    ? Rule::unique('kitchen_ingredients', 'name')->ignore($ingredientId)
                    : Rule::unique('kitchen_ingredients', 'name')
            ],
            'description' => 'nullable|string|max:1000',
            'unit' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s\-\/\.]+$/',
            'current_stock' => $isUpdate 
                ? 'required|numeric|min:0|max:999999.9999'
                : 'sometimes|numeric|min:0|max:999999.9999',
            'min_stock_level' => 'required|numeric|min:0|max:999999.9999',
            'cost_per_unit' => 'required|numeric|min:0|max:999999.99',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ingredient name is required.',
            'name.unique' => 'An ingredient with this name already exists.',
            'name.max' => 'Ingredient name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'unit.required' => 'Unit is required.',
            'unit.regex' => 'Unit must contain only letters, numbers, spaces, hyphens, slashes, and dots.',
            'current_stock.numeric' => 'Current stock must be a valid number.',
            'current_stock.min' => 'Current stock cannot be negative.',
            'current_stock.max' => 'Current stock cannot exceed 999,999.9999.',
            'min_stock_level.required' => 'Minimum stock level is required.',
            'min_stock_level.numeric' => 'Minimum stock level must be a valid number.',
            'min_stock_level.min' => 'Minimum stock level cannot be negative.',
            'cost_per_unit.required' => 'Cost per unit is required.',
            'cost_per_unit.numeric' => 'Cost per unit must be a valid number.',
            'cost_per_unit.min' => 'Cost per unit cannot be negative.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'ingredient name',
            'description' => 'description',
            'unit' => 'unit',
            'current_stock' => 'current stock',
            'min_stock_level' => 'minimum stock level',
            'cost_per_unit' => 'cost per unit',
            'supplier_id' => 'supplier',
            'active' => 'active status',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert checkbox values to boolean
        if ($this->has('active')) {
            $this->merge([
                'active' => $this->boolean('active'),
            ]);
        }

        // Clean up numeric inputs
        if ($this->has('current_stock')) {
            $this->merge([
                'current_stock' => (float) str_replace(',', '', $this->input('current_stock')),
            ]);
        }

        if ($this->has('min_stock_level')) {
            $this->merge([
                'min_stock_level' => (float) str_replace(',', '', $this->input('min_stock_level')),
            ]);
        }

        if ($this->has('cost_per_unit')) {
            $this->merge([
                'cost_per_unit' => (float) str_replace(',', '', $this->input('cost_per_unit')),
            ]);
        }
    }
}
