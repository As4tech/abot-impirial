<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kitchen Stock</h2>
            <div class="flex items-center gap-2">
                @if($lowStockCount > 0)
                    <a href="{{ route('kitchen-purchases.low-stock') }}" class="inline-flex items-center gap-2 text-sm bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span>Restock {{ $lowStockCount }} Items</span>
                    </a>
                @endif
                <a href="{{ route('kitchen-stock.create') }}" class="inline-flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Ingredient</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2 sm:p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded text-sm">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded text-sm">{{ session('error') }}</div>
        @endif

        <!-- Search and Filters -->
        <form method="GET" class="bg-white shadow-sm rounded-lg p-3 sm:p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ingredients..." class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="active" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('kitchen-stock.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Clear</a>
                </div>
            </div>
        </form>

        <!-- Ingredients Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden lg:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Level</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost per Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($ingredients as $ingredient)
                            <tr class="{{ $ingredient->isLowStock() ? 'bg-red-50' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $ingredient->name }}</div>
                                    @if($ingredient->description)
                                        <div class="text-sm text-gray-500">{{ $ingredient->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="{{ $ingredient->isLowStock() ? 'text-red-600 font-medium' : '' }}">
                                        {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ number_format($ingredient->min_stock_level, 2) }} {{ $ingredient->unit }}</td>
                                <td class="px-4 py-3"><x-currency :amount="$ingredient->cost_per_unit" /></td>
                                <td class="px-4 py-3">{{ $ingredient->supplier?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($ingredient->isLowStock())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $ingredient->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $ingredient->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('kitchen-stock.show', $ingredient) }}" class="text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('kitchen-stock.edit', $ingredient) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('kitchen-stock.destroy', $ingredient) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Delete" onclick="return confirm('Are you sure?')">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-gray-500 text-center">No ingredients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card Layout -->
            <div class="lg:hidden">
                @forelse ($ingredients as $ingredient)
                    <div class="bg-white shadow-sm rounded-lg p-4 mb-4 {{ $ingredient->isLowStock() ? 'border-l-4 border-red-500' : '' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-medium text-lg">{{ $ingredient->name }}</h3>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('kitchen-stock.show', $ingredient) }}" class="text-blue-600 hover:text-blue-900 p-1" title="View">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('kitchen-stock.edit', $ingredient) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('kitchen-stock.destroy', $ingredient) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Delete" onclick="return confirm('Are you sure?')">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if($ingredient->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $ingredient->description }}</p>
                        @endif

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Current Stock</label>
                                <div class="{{ $ingredient->isLowStock() ? 'text-red-600 font-medium' : '' }}">
                                    {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Min Level</label>
                                <div>{{ number_format($ingredient->min_stock_level, 2) }} {{ $ingredient->unit }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Cost per Unit</label>
                                <div><x-currency :amount="$ingredient->cost_per_unit" /></div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Supplier</label>
                                <div>{{ $ingredient->supplier?->name ?? '—' }}</div>
                            </div>
                        </div>
                        
                        <div>
                            @if($ingredient->isLowStock())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ⚠️ Low Stock
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $ingredient->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $ingredient->active ? '✓ Active' : '○ Inactive' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-6 text-center text-gray-500">
                        No ingredients found.
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            {{ $ingredients->links() }}
        </div>
    </div>
</x-app-layout>
