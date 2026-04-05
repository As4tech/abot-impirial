<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{ $kitchenStock->name }}</h2>
            <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded">Back to Stock</a>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('kitchen-stock.update', $kitchenStock) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $kitchenStock->name) }}" required class="w-full border rounded px-3 py-2" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded px-3 py-2">{{ old('description', $kitchenStock->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                        <input type="text" name="unit" value="{{ old('unit', $kitchenStock->unit) }}" placeholder="e.g., kg, L, g, ml, pcs" required class="w-full border rounded px-3 py-2" />
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Level *</label>
                        <input type="number" name="min_stock_level" value="{{ old('min_stock_level', $kitchenStock->min_stock_level) }}" step="0.0001" min="0" required class="w-full border rounded px-3 py-2" />
                        @error('min_stock_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Unit *</label>
                        <input type="number" name="cost_per_unit" value="{{ old('cost_per_unit', $kitchenStock->cost_per_unit) }}" step="0.01" min="0" required class="w-full border rounded px-3 py-2" />
                        @error('cost_per_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $kitchenStock->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', $kitchenStock->active) ? 'checked' : '' }} class="rounded border-gray-300" />
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update Ingredient</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
