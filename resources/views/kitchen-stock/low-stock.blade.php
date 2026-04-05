<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Low Stock Alerts</h2>
            <a href="{{ route('kitchen-stock.index') }}" class="px-4 py-2 border rounded">Back to Stock</a>
        </div>
    </x-slot>

    <div class="p-4">
        @if($ingredients->count() > 0)
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                <strong>Attention:</strong> {{ $ingredients->count() }} ingredient(s) are below their minimum stock level.
            </div>
        @else
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                <strong>Good news:</strong> All ingredients are above their minimum stock level.
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Level</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shortfall</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($ingredients as $ingredient)
                        <tr class="bg-red-50">
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $ingredient->name }}</div>
                                @if($ingredient->description)
                                    <div class="text-sm text-gray-500">{{ $ingredient->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-red-600 font-medium">
                                    {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ number_format($ingredient->min_stock_level, 2) }} {{ $ingredient->unit }}</td>
                            <td class="px-4 py-3">
                                <span class="text-red-600 font-medium">
                                    {{ number_format($ingredient->min_stock_level - $ingredient->current_stock, 2) }} {{ $ingredient->unit }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $ingredient->supplier?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kitchen-stock.show', $ingredient) }}" class="text-blue-600 hover:text-blue-900 p-1" title="View & Adjust">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('kitchen-stock.edit', $ingredient) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-gray-500 text-center">No ingredients are below minimum stock level.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ingredients->hasPages())
            <div class="mt-4">
                {{ $ingredients->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
