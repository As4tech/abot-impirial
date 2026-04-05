<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kitchen Purchases</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('kitchen-purchases.low-stock') }}" class="inline-flex items-center gap-2 text-sm bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span>Restock Low Items</span>
                </a>
                <a href="{{ route('kitchen-purchases.create') }}" class="inline-flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Purchase</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <!-- Filters -->
        <form method="GET" class="bg-white shadow-sm rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient</label>
                    <select name="ingredient_id" class="w-full border rounded px-3 py-2">
                        <option value="">All ingredients</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" {{ request('ingredient_id') == $ingredient->id ? 'selected' : '' }}>
                                {{ $ingredient->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id" class="w-full border rounded px-3 py-2">
                        <option value="">All suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('kitchen-purchases.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Clear</a>
                </div>
            </div>
        </form>

        <!-- Purchases Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingredient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-4 py-3">{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $purchase->ingredient->name }}</td>
                            <td class="px-4 py-3 text-green-600">
                                +{{ number_format($purchase->quantity, 2) }} {{ $purchase->ingredient->unit }}
                            </td>
                            <td class="px-4 py-3"><x-currency :amount="$purchase->unit_cost" /></td>
                            <td class="px-4 py-3 font-medium">
                                <x-currency :amount="$purchase->quantity * $purchase->unit_cost" />
                            </td>
                            <td class="px-4 py-3">{{ $purchase->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-3">{{ $purchase->notes ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('kitchen-purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-900 p-1" title="View">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-gray-500 text-center">No purchases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $purchases->links() }}
        </div>
    </div>
</x-app-layout>
