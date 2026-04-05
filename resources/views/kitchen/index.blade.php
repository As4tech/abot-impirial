@php
    $currency = function_exists('setting') ? (setting('pos.currency') ?: 'PHP') : 'PHP';
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kitchen (KOT)</h2>
            <form method="GET" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="border rounded px-2 py-1">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm">Status</label>
                    <select name="status" class="border rounded px-2 py-1">
                        <option value="">All</option>
                        <option value="Pending" {{ ($status ?? '')==='Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Preparing" {{ ($status ?? '')==='Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Served" {{ ($status ?? '')==='Served' ? 'selected' : '' }}>Served</option>
                    </select>
                </div>
                <button class="bg-gray-800 hover:bg-black text-white px-3 py-1 rounded">Filter</button>
                @if(request()->has('date') || request()->has('status'))
                <a href="{{ route('kitchen.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">Clear</a>
                @endif
            </form>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <!-- Bulk Actions -->
        @if($orders->where('status', 'Pending')->count() > 0)
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('kitchen.orders.bulk-update') }}" onsubmit="return confirm('Mark all pending orders as served? This will clear them from the screen.');">
                @csrf
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-medium">Bulk Actions</span>
                        <span class="text-sm text-gray-600 ml-2">{{ $orders->where('status', 'Pending')->count() }} pending orders</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="status" value="Served">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Mark All as Served
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($orders as $order)
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold">Order #{{ $order->id }}</div>
                            <div class="text-sm text-gray-600">Status: {{ $order->status }}</div>
                            <div class="text-sm text-gray-600">Type: {{ ucfirst($order->order_type) }}</div>
                        </div>
                        <form method="POST" action="{{ route('kitchen.orders.status', $order) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="status" class="border rounded px-2 py-1">
                                <option value="Pending" {{ $order->status==='Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Preparing" {{ $order->status==='Preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="Served" {{ $order->status==='Served' ? 'selected' : '' }}>Served</option>
                            </select>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Update</button>
                        </form>
                    </div>
                    <div class="mt-3">
                        <ul class="list-disc pl-5 text-sm text-gray-800">
                            @foreach ($order->items as $line)
                                <li>
                                    {{ $line->quantity }} × {{ $line->product?->name ?? $line->menuItem?->name }}
                                    <span class="text-gray-500">— {{ $currency }}{{ number_format($line->price, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-3 text-right font-semibold">Total: {{ $currency }}{{ number_format($order->total_amount, 2) }}</div>
                </div>
            @empty
                <div class="text-gray-600">No orders to display.</div>
            @endforelse
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
