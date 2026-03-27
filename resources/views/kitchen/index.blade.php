<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kitchen (KOT)</h2>
            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm">Status</label>
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="Pending" {{ ($status ?? '')==='Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Preparing" {{ ($status ?? '')==='Preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="Served" {{ ($status ?? '')==='Served' ? 'selected' : '' }}>Served</option>
                </select>
                <button class="bg-gray-800 hover:bg-black text-white px-3 py-1 rounded">Filter</button>
            </form>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
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
                                    <span class="text-gray-500">— ₱{{ number_format($line->price, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-3 text-right font-semibold">Total: ₱{{ number_format($order->total_amount, 2) }}</div>
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
