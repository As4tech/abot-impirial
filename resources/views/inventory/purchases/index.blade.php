<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Purchases</h2>
            <a href="{{ route('inventory.purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Purchase</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <form method="GET" class="bg-white shadow-sm rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supplier</label>
                    <select name="supplier_id" class="w-full border rounded px-3 py-2">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" {{ ($supplierId ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('inventory.purchases.index') }}" class="px-4 py-2 border rounded">Clear</a>
                </div>
            </div>
        </form>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($purchases as $p)
                    <tr>
                        <td class="px-4 py-3 font-medium">#{{ $p->id }}</td>
                        <td class="px-4 py-3">{{ $p->supplier?->name ?? '—' }}</td>
                        <td class="px-4 py-3"><x-currency :amount="$p->total_cost" /></td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $p->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('inventory.purchases.show', $p) }}" title="View" class="inline-flex items-center text-blue-600 hover:underline">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12 18 19.5 12 19.5 1.5 12 1.5 12zm10.5 3a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                <span class="sr-only">View</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="5">No purchases found.</td>
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
