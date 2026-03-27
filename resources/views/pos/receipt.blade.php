<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500">{{ function_exists('setting') ? setting('general.business_name', config('app.name')) : config('app.name') }}</div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Receipt #{{ $order->id }}</h2>
            </div>
            <div class="space-x-2">
                <a href="{{ route('pos.index') }}" class="px-4 py-2 border rounded">Back to POS</a>
                <a href="{{ route('pos.receipt.thermal', $order) }}" class="px-4 py-2 border rounded">Thermal 80mm</a>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-800 text-white rounded">Print</button>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        @if (session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6 print:p-0 print:shadow-none print:bg-transparent">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <div class="text-sm text-gray-600">Order #</div>
                    <div class="font-medium">{{ $order->id }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Date</div>
                    <div class="font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Cashier</div>
                    <div class="font-medium">{{ $order->user?->name ?? '—' }}</div>
                </div>
            </div>

            <div class="overflow-hidden rounded border border-gray-200 mb-4">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Line total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $it)
                            <tr>
                                <td class="px-3 py-2">{{ $it->product?->name ?? $it->menuItem?->name }}</td>
                                <td class="px-3 py-2 text-right">{{ $it->quantity }}</td>
                                <td class="px-3 py-2 text-right"><x-currency :amount="$it->price" /></td>
                                <td class="px-3 py-2 text-right"><x-currency :amount="$it->price * $it->quantity" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <div class="w-full md:w-80 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span>Total</span>
                        <span><x-currency :amount="$order->total_amount" /></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Paid</span>
                        <span><x-currency :amount="$paid" /></span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Balance</span>
                        <span><x-currency :amount="$balance" /></span>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Payments</h3>
                    <div class="overflow-hidden rounded border border-gray-200">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($order->payments as $pay)
                                    <tr>
                                        <td class="px-3 py-2 capitalize">{{ str_replace('_',' ', $pay->method) }}</td>
                                        <td class="px-3 py-2 capitalize">{{ $pay->status }}</td>
                                        <td class="px-3 py-2 text-right"><x-currency :amount="$pay->amount" /></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 text-gray-500">No payments yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Add Payment</h3>
                    <form method="POST" action="{{ route('pos.payments.store') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div>
                            <label class="block text-sm font-medium">Method</label>
                            <select name="method" class="w-full border rounded px-2 py-2" required>
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Amount</label>
                            <input type="number" step="0.01" min="0.01" name="amount" value="{{ number_format($balance, 2, '.', '') }}" class="w-full border rounded px-2 py-2" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Status</label>
                            <select name="status" class="w-full border rounded px-2 py-2">
                                <option value="paid" selected>Paid</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Record Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            header, nav, aside, .no-print { display: none !important; }
            main, .print\:p-0 { padding: 0 !important; }
            .print\:shadow-none { box-shadow: none !important; }
            .print\:bg-transparent { background: transparent !important; }
        }
    </style>
</x-app-layout>
