<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restock Low Items</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('kitchen-purchases.index') }}" class="px-4 py-2 border rounded">Back to Purchases</a>
                <a href="{{ route('kitchen-purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Purchase</a>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        @if($ingredients->count() > 0)
            <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded mb-4">
                <strong>Restock Needed:</strong> {{ $ingredients->count() }} ingredient(s) are below minimum stock level. 
                Click "Add to Purchase" to create a purchase order with suggested quantities.
            </div>
        @else
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                <strong>All Stock Levels Good:</strong> No ingredients are currently below minimum stock level.
            </div>
        @endif

        @if($ingredients->count() > 0)
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingredient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Level</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shortfall</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Suggested Order</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Cost</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($ingredients as $ingredient)
                            <tr class="bg-red-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $ingredient->name }}</div>
                                    @if($ingredient->description)
                                        <div class="text-sm text-gray-500">{{ $ingredient->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-red-600 font-medium">
                                        {{ number_format($ingredient->current_stock, 4) }} {{ $ingredient->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ number_format($ingredient->min_stock_level, 4) }} {{ $ingredient->unit }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-red-600 font-medium">
                                        {{ number_format($ingredient->min_stock_level - $ingredient->current_stock, 4) }} {{ $ingredient->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-blue-600 font-medium">
                                        {{ number_format(max($ingredient->min_stock_level * 2 - $ingredient->current_stock, $ingredient->min_stock_level), 4) }} {{ $ingredient->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $ingredient->supplier?->name ?? '—' }}</td>
                                <td class="px-4 py-3"><x-currency :amount="$ingredient->cost_per_unit" /></td>
                                <td class="px-4 py-3 text-right">
                                    <button onclick="addToPurchase({{ $ingredient->id }}, '{{ $ingredient->name }}', {{ $ingredient->min_stock_level * 2 - $ingredient->current_stock }}, '{{ $ingredient->unit }}', {{ $ingredient->cost_per_unit }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Add to Purchase
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Quick Purchase Form -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Purchase Order</h3>
                <form method="POST" action="{{ route('kitchen-purchases.store') }}" id="quickPurchaseForm">
                    @csrf
                    <input type="hidden" name="purchase_date" value="{{ now()->format('Y-m-d') }}" />
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number (Optional)</label>
                        <input type="text" name="invoice_number" placeholder="e.g., INV-2024-001" class="w-full border rounded px-3 py-2" />
                    </div>

                    <div id="selectedItems" class="space-y-3 mb-4">
                        <!-- Items will be added here dynamically -->
                    </div>

                    @if(count($selectedItems ?? []) > 0)
                        <div class="mb-4 p-3 bg-blue-50 rounded">
                            <div class="text-sm">
                                <strong>Total Items:</strong> <span id="quickTotalItems">0</span><br>
                                <strong>Estimated Total:</strong> <span id="quickTotalCost">0.00</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="clearSelection()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Clear Selection</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded" {{ !($selectedItems ?? []) ? 'disabled' : '' }}>
                            Create Purchase Order
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        let selectedItems = [];

        function addToPurchase(id, name, suggestedQuantity, unit, cost) {
            // Check if already added
            if (selectedItems.find(item => item.id === id)) {
                alert('This ingredient is already in the purchase list.');
                return;
            }

            const item = {
                id: id,
                name: name,
                quantity: suggestedQuantity,
                unit: unit,
                cost: cost
            };
            
            selectedItems.push(item);
            updateSelectedItemsDisplay();
        }

        function removeFromPurchase(id) {
            selectedItems = selectedItems.filter(item => item.id !== id);
            updateSelectedItemsDisplay();
        }

        function updateSelectedItemsDisplay() {
            const container = document.getElementById('selectedItems');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (selectedItems.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No items selected. Click "Add to Purchase" from the table above.</p>';
                submitButton.disabled = true;
                document.getElementById('quickTotalItems').textContent = '0';
                document.getElementById('quickTotalCost').textContent = '0.00';
            } else {
                let html = '';
                let totalCost = 0;
                
                selectedItems.forEach((item, index) => {
                    const itemTotal = item.quantity * item.cost;
                    totalCost += itemTotal;
                    
                    html += `
                        <div class="flex items-center justify-between p-3 border rounded">
                            <div class="flex-1">
                                <div class="font-medium">${item.name}</div>
                                <div class="text-sm text-gray-600">
                                    <input type="hidden" name="items[${index}][ingredient_id]" value="${item.id}" />
                                    <input type="number" name="items[${index}][quantity]" value="${item.quantity}" step="0.0001" min="0.0001" required 
                                           class="inline w-20 border rounded px-2 py-1" onchange="updateItemQuantity(${index}, this.value)" />
                                    ${item.unit} × 
                                    <input type="number" name="items[${index}][unit_cost]" value="${item.cost}" step="0.01" min="0" required 
                                           class="inline w-20 border rounded px-2 py-1" onchange="updateItemCost(${index}, this.value)" />
                                    = <span class="font-medium">${itemTotal.toFixed(2)}</span>
                                </div>
                            </div>
                            <button type="button" onclick="removeFromPurchase(${item.id})" class="ml-3 text-red-600 hover:text-red-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                submitButton.disabled = false;
                document.getElementById('quickTotalItems').textContent = selectedItems.length;
                document.getElementById('quickTotalCost').textContent = totalCost.toFixed(2);
            }
        }

        function updateItemQuantity(index, quantity) {
            selectedItems[index].quantity = parseFloat(quantity) || 0;
            updateSelectedItemsDisplay();
        }

        function updateItemCost(index, cost) {
            selectedItems[index].cost = parseFloat(cost) || 0;
            updateSelectedItemsDisplay();
        }

        function clearSelection() {
            selectedItems = [];
            updateSelectedItemsDisplay();
        }

        // Initialize display
        updateSelectedItemsDisplay();
    </script>
</x-app-layout>
