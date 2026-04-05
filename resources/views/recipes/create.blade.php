<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Recipe Ingredient</h2>
            <a href="{{ route('recipes.index') }}" class="px-4 py-2 border rounded">Back to Recipes</a>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('recipes.store') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Menu Item *</label>
                        <select name="menu_item_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Select menu item</option>
                            @foreach ($menuItems as $item)
                                <option value="{{ $item->id }}" {{ old('menu_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('menu_item_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                        <select name="kitchen_ingredient_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Select ingredient</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" {{ old('kitchen_ingredient_id') == $ingredient->id ? 'selected' : '' }}>
                                    {{ $ingredient->name }} ({{ $ingredient->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('kitchen_ingredient_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Required *</label>
                        <input type="number" name="quantity_required" value="{{ old('quantity_required') }}" step="0.0001" min="0.0001" required class="w-full border rounded px-3 py-2" />
                        @error('quantity_required')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" placeholder="e.g., kg, L, g, ml, pcs" required class="w-full border rounded px-3 py-2" />
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded">
                    <strong>Note:</strong> This defines how much of the ingredient is required to prepare one serving of the menu item.
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('recipes.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add to Recipe</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
