<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Kitchen Purchase</h2>
            <a href="{{ route('kitchen-purchases.index') }}" class="px-4 py-2 border rounded">Back to Purchases</a>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('kitchen-purchases.store') }}" id="purchaseForm">
                @csrf
                
                <!-- Purchase Details -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Purchase Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date *</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required class="w-full border rounded px-3 py-2" />
                            @error('purchase_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Optional" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select name="supplier_id" id="supplierFilter" class="w-full border rounded px-3 py-2">
                                <option value="">All suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Purchase Items</h3>
                        <button type="button" onclick="addItem()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            + Add Item
                        </button>
                    </div>
                    
                    <div id="itemsContainer" class="space-y-4">
                        <!-- Initial item row -->
                        <div class="item-row border rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                                    <select name="items[0][ingredient_id]" required class="ingredient-select w-full border rounded px-3 py-2">
                                        <option value="">Select ingredient</option>
                                        @foreach ($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}" data-supplier="{{ $ingredient->supplier_id ?? '' }}" data-unit="{{ $ingredient->unit }}" data-cost="{{ $ingredient->cost_per_unit }}">
                                                {{ $ingredient->name }} ({{ $ingredient->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" step="0.0001" min="0.0001" required class="quantity-input w-full border rounded px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost *</label>
                                    <input type="number" name="items[0][unit_cost]" step="0.01" min="0" required class="cost-input w-full border rounded px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                                    <div class="total-display w-full border rounded px-3 py-2 bg-gray-50 font-medium">0.00</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <input type="text" name="items[0][notes]" placeholder="Optional" class="w-full border rounded px-3 py-2" />
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="stock-info">Current stock: —</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm text-gray-600">Total Items: <span id="totalItems">1</span></div>
                            <div class="text-sm text-gray-600">Total Cost: <span id="totalCost" class="font-semibold text-lg">0.00</span></div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <div>Low Stock Alert: Items below minimum will be highlighted</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('kitchen-purchases.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Record Purchase</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 1;

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'item-row border rounded-lg p-4';
            newRow.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Item ${itemCount + 1}</span>
                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                        <select name="items[${itemCount}][ingredient_id]" required class="ingredient-select w-full border rounded px-3 py-2">
                            <option value="">Select ingredient</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" data-supplier="{{ $ingredient->supplier_id ?? '' }}" data-unit="{{ $ingredient->unit }}" data-cost="{{ $ingredient->cost_per_unit }}" data-stock="{{ $ingredient->current_stock }}" data-min-stock="{{ $ingredient->min_stock_level }}">
                                    {{ $ingredient->name }} ({{ $ingredient->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" name="items[${itemCount}][quantity]" step="0.0001" min="0.0001" required class="quantity-input w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost *</label>
                        <input type="number" name="items[${itemCount}][unit_cost]" step="0.01" min="0" required class="cost-input w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                        <div class="total-display w-full border rounded px-3 py-2 bg-gray-50 font-medium">0.00</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <input type="text" name="items[${itemCount}][notes]" placeholder="Optional" class="w-full border rounded px-3 py-2" />
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <span class="stock-info">Current stock: —</span>
                </div>
            `;
            container.appendChild(newRow);
            attachEventListeners(newRow);
            itemCount++;
            updateTotals();
        }

        function removeItem(button) {
            button.closest('.item-row').remove();
            updateTotals();
        }

        function attachEventListeners(row) {
            const ingredientSelect = row.querySelector('.ingredient-select');
            const quantityInput = row.querySelector('.quantity-input');
            const costInput = row.querySelector('.cost-input');
            const totalDisplay = row.querySelector('.total-display');
            const stockInfo = row.querySelector('.stock-info');

            ingredientSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option.value) {
                    costInput.value = option.dataset.cost || 0;
                    stockInfo.textContent = `Current stock: ${option.dataset.stock || 0} (Min: ${option.dataset.min_stock || 0})`;
                    
                    // Highlight if low stock
                    const currentStock = parseFloat(option.dataset.stock || 0);
                    const minStock = parseFloat(option.dataset.min_stock || 0);
                    if (currentStock <= minStock) {
                        row.classList.add('bg-red-50', 'border-red-200');
                    } else {
                        row.classList.remove('bg-red-50', 'border-red-200');
                    }
                } else {
                    stockInfo.textContent = 'Current stock: —';
                    row.classList.remove('bg-red-50', 'border-red-200');
                }
                updateTotals();
            });

            quantityInput.addEventListener('input', updateTotals);
            costInput.addEventListener('input', updateTotals);
        }

        function updateTotals() {
            let totalCost = 0;
            let itemCount = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
                const total = quantity * cost;
                
                row.querySelector('.total-display').textContent = total.toFixed(2);
                totalCost += total;
                if (quantity > 0) itemCount++;
            });

            document.getElementById('totalCost').textContent = totalCost.toFixed(2);
            document.getElementById('totalItems').textContent = itemCount;
        }

        // Initialize event listeners for the first row
        document.addEventListener('DOMContentLoaded', function() {
            attachEventListeners(document.querySelector('.item-row'));
        });

        // Filter ingredients by supplier
        document.getElementById('supplierFilter').addEventListener('change', function() {
            const supplierId = this.value;
            document.querySelectorAll('.ingredient-select').forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (!option.value) return;
                    const optionSupplierId = option.dataset.supplier;
                    option.style.display = (!supplierId || optionSupplierId === supplierId) ? '' : 'none';
                });
            });
        });
    </script>
</x-app-layout>
