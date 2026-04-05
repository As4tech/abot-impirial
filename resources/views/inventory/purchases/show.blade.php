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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Line Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($purchase->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product?->name }}</td>
                        <td class="px-4 py-3">{{ number_format($item->quantity, 3) }}</td>
                        <td class="px-4 py-3"><x-currency :amount="$item->cost_price" /></td>
                        <td class="px-4 py-3"><x-currency :amount="$item->quantity * $item->cost_price" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
