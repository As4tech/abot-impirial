<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Purchase #{{ $purchase->id }}</h2>
            <a href="{{ route('inventory.purchases.index') }}" class="px-4 py-2 border rounded">Back to list</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-sm text-gray-600">Supplier</div>
                    <div class="font-medium">{{ $purchase->supplier?->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Cost</div>
                    <div class="font-medium"><x-currency :amount="$purchase->total_cost" /></div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Date</div>
                    <div class="font-medium">{{ $purchase->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($purchase->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product?->name }}</td>
                        <td class="px-4 py-3">{{ number_format($item->quantity, 3) }}</td>
                        <td class="px-4 py-3"><x-currency :amount="$item->cost_price" /></td>
                        <td class="px-4 py-3"><x-currency :amount="$item->quantity * $item->cost_price" /></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('inventory.purchases.edit', $purchase->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit Purchase">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('inventory.purchases.destroy', $purchase->id) }}" onsubmit="return confirm('Are you sure you want to delete this purchase? This will reverse all stock movements.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete Purchase">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
