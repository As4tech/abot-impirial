<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Kitchen Ingredient</h2>
            <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded">Back to Stock</a>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('kitchen-stock.store') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                        @error('description')
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock *</label>
                        <input type="number" name="current_stock" value="{{ old('current_stock') }}" step="0.0001" min="0" required class="w-full border rounded px-3 py-2" />
                        @error('current_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Level *</label>
                        <input type="number" name="min_stock_level" value="{{ old('min_stock_level') }}" step="0.0001" min="0" required class="w-full border rounded px-3 py-2" />
                        @error('min_stock_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Unit *</label>
                        <input type="number" name="cost_per_unit" value="{{ old('cost_per_unit') }}" step="0.01" min="0" required class="w-full border rounded px-3 py-2" />
                        @error('cost_per_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                            <input type="checkbox" name="active" value="1" {{ old('active') ? 'checked' : '' }} class="rounded border-gray-300" />
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2" id="submitBtn">
                        <svg class="h-4 w-4" id="loadingIcon" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span id="submitText">Add Ingredient</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
<script>
        document.getElementById('submitBtn').addEventListener('click', function() {
            const form = this.closest('form');
            const submitBtn = this;
            const loadingIcon = document.getElementById('loadingIcon');
            const submitText = document.getElementById('submitText');
            
            // Show loading state
            submitBtn.disabled = true;
            loadingIcon.style.display = 'block';
            submitText.textContent = 'Adding...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Submit form after a brief delay to show loading state
            setTimeout(() => {
                form.submit();
            }, 100);
        });
        
        // Prevent double submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</x-app-layout>
