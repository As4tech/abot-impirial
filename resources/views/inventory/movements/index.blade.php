<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stock Movements</h2>
    </x-slot>

    <div class="p-4 space-y-4">
        <form method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-sm font-medium">Product</label>
                <select name="product_id" class="border rounded px-3 py-2 w-72">
                    <option value="">All</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" {{ ($productId ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Type</label>
                <select name="type" class="border rounded px-3 py-2 w-48">
                    <option value="">All</option>
                    <option value="in" {{ ($type ?? '')==='in' ? 'selected' : '' }}>In</option>
                    <option value="out" {{ ($type ?? '')==='out' ? 'selected' : '' }}>Out</option>
                    <option value="adjustment" {{ ($type ?? '')==='adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>
            <div>
                <button class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">Filter</button>
            </div>
        </form>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($movements as $m)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $m->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $m->product?->name }}</td>
                        <td class="px-4 py-3">{{ ucfirst($m->type) }}</td>
                        <td class="px-4 py-3">{{ number_format($m->quantity, 3) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $m->reference }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="5">No movements found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $movements->links() }}
        </div>
    </div>
</x-app-layout>
