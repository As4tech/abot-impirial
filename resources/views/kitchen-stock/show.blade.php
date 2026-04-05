<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $kitchenStock->name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded">Back to Stock</a>
                <a href="{{ route('kitchen-stock.edit', $kitchenStock) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <!-- Ingredient Details -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <div class="text-sm text-gray-600">Current Stock</div>
                    <div class="text-2xl font-bold {{ $kitchenStock->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ number_format($kitchenStock->current_stock, 2) }} {{ $kitchenStock->unit }}
                    </div>
                    @if($kitchenStock->isLowStock())
                        <div class="text-sm text-red-600">Below minimum level</div>
                    @endif
                </div>
                <div>
                    <div class="text-sm text-gray-600">Minimum Level</div>
                    <div class="text-lg font-semibold">{{ number_format($kitchenStock->min_stock_level, 2) }} {{ $kitchenStock->unit }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Cost per Unit</div>
                    <div class="text-lg font-semibold"><x-currency :amount="$kitchenStock->cost_per_unit" /></div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Supplier</div>
                    <div class="text-lg font-semibold">{{ $kitchenStock->supplier?->name ?? '—' }}</div>
                </div>
            </div>
            @if($kitchenStock->description)
                <div class="mt-4 pt-4 border-t">
                    <div class="text-sm text-gray-600">Description</div>
                    <div class="mt-1">{{ $kitchenStock->description }}</div>
                </div>
            @endif
        </div>

        <!-- Stock Adjustment -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Adjust Stock</h3>
            <form method="POST" action="{{ route('kitchen-stock.adjust', $kitchenStock) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select name="type" required class="w-full border rounded px-3 py-2">
                        <option value="purchase">Purchase (Add Stock)</option>
                        <option value="waste">Waste (Remove Stock)</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="quantity" step="0.0001" required class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                    <input type="number" name="unit_cost" step="0.01" min="0" value="{{ $kitchenStock->cost_per_unit }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <input type="text" name="notes" class="w-full border rounded px-3 py-2" />
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center justify-center gap-2" id="adjustBtn">
                        <svg class="h-4 w-4" id="adjustLoadingIcon" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span id="adjustText">Adjust</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Recipe Usage -->
        @if($kitchenStock->recipes->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Used in Recipes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Required</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($kitchenStock->recipes as $recipe)
                                <tr>
                                    <td class="px-4 py-3">{{ $recipe->menuItem->name }}</td>
                                    <td class="px-4 py-3">{{ number_format($recipe->quantity_required, 2) }}</td>
                                    <td class="px-4 py-3">{{ $recipe->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Movements -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Stock Movements</h3>
            @if($recentMovements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentMovements as $movement)
                                <tr>
                                    <td class="px-4 py-3">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            {{ $movement->type === 'purchase' ? 'bg-green-100 text-green-800' : 
                                               ($movement->type === 'waste' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $movement->type_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 {{ $movement->quantity < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $movement->quantity_formatted }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($movement->unit_cost)
                                            <x-currency :amount="$movement->unit_cost" />
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $movement->user?->name ?? 'System' }}</td>
                                    <td class="px-4 py-3">{{ $movement->notes ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No stock movements recorded yet.</p>
            @endif
        </div>
    </div>
<script>
        document.getElementById('adjustBtn')?.addEventListener('click', function() {
            const form = this.closest('form');
            const adjustBtn = this;
            const loadingIcon = document.getElementById('adjustLoadingIcon');
            const adjustText = document.getElementById('adjustText');
            
            // Show loading state
            adjustBtn.disabled = true;
            loadingIcon.style.display = 'block';
            adjustText.textContent = 'Adjusting...';
            adjustBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Submit form after a brief delay to show loading state
            setTimeout(() => {
                form.submit();
            }, 100);
        });
        
        // Prevent double submission for adjustment form
        document.querySelector('form[action*="adjust"]')?.addEventListener('submit', function(e) {
            const adjustBtn = document.getElementById('adjustBtn');
            if (adjustBtn && adjustBtn.disabled) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</x-app-layout>
