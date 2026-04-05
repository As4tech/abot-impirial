<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
            @can('products.create')
            <a href="{{ route('inventory.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Product</a>
            @endcan
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <form method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-sm font-medium">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search products..." class="border rounded px-3 py-2 w-60" />
            </div>
            <div>
                <label class="block text-sm font-medium">Category</label>
                <select name="category_id" class="border rounded px-3 py-2 w-60">
                    <option value="">All</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($categoryId ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selling</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr class="{{ $product->stock_quantity <= $threshold ? 'bg-amber-50' : '' }}">
                        <td class="px-4 py-3">
                            @if($product->image_path)
                                <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded border" />
                            @else
                                <div class="h-12 w-12 rounded border bg-gray-50 flex items-center justify-center text-gray-400 text-xs">N/A</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->category?->name }}</td>
                        <td class="px-4 py-3">{{ $product->unit }}</td>
                        <td class="px-4 py-3 font-medium">{{ number_format($product->stock_quantity, 3) }}</td>
                        <td class="px-4 py-3">{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($product->cost_price ?? 0, 2) }}</td>
                        <td class="px-4 py-3">{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($product->selling_price ?? $product->price, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($product->stock_quantity <= $threshold)
                                <span class="inline-block text-xs text-amber-700 bg-amber-100 border border-amber-200 rounded px-2 py-1">Low stock</span>
                            @else
                                <span class="inline-block text-xs text-green-700 bg-green-100 border border-green-200 rounded px-2 py-1">Stocked</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @can('products.update')
                            <a href="{{ route('inventory.products.edit', $product) }}" title="Edit" class="inline-flex items-center text-blue-600 hover:underline">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M16.5 3.964a2.5 2.5 0 113.536 3.536L7.5 20.036H4v-3.5L16.5 3.964z"/></svg>
                                <span class="sr-only">Edit</span>
                            </a>
                            @endcan
                            @can('products.delete')
                            <form method="POST" action="{{ route('inventory.products.destroy', $product) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button title="Delete" class="inline-flex items-center text-red-600 hover:underline" onclick="return confirm('Delete this product?')">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-7 0h8"/></svg>
                                    <span class="sr-only">Delete</span>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="9">No products found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
