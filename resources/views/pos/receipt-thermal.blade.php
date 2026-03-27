<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thermal Receipt #{{ $order->id }}</h2>
            <div class="space-x-2">
                <a href="{{ route('pos.receipt', $order) }}" class="px-3 py-1 border rounded text-sm">Standard</a>
                <button onclick="window.print()" class="px-3 py-1 bg-gray-800 text-white rounded text-sm">Print</button>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="mx-auto bg-white shadow-sm rounded p-4 thermal:w-80 print:w-80">
            <div class="text-center mb-3">
                <div class="font-bold text-base">{{ function_exists('setting') ? setting('general.business_name', config('app.name', 'ABot Imperial')) : config('app.name', 'ABot Imperial') }}</div>
                <div class="text-xs text-gray-600">Receipt #{{ $order->id }} · {{ $order->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="text-xs mb-2">
                <div>Cashier: <span class="font-medium">{{ $order->user?->name ?? '—' }}</span></div>
                @if($order->order_type === 'room')
                    <div>Room: <span class="font-medium">{{ optional($order->room)->room_number }}</span></div>
                @endif
            </div>

            <div class="border-t border-b border-gray-200 py-2 mb-2">
                @foreach ($order->items as $it)
                    <div class="flex justify-between text-xs">
                        <div class="pr-2 truncate">{{ $it->product?->name ?? $it->menuItem?->name }}</div>
                        <div class="whitespace-nowrap">{{ $it->quantity }} x {{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($it->price, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="text-xs space-y-1 mb-2">
                <div class="flex justify-between"><span>Total</span><span>{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($order->total_amount, 2) }}</span></div>
                <div class="flex justify-between"><span>Paid</span><span>{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($paid, 2) }}</span></div>
                <div class="flex justify-between font-semibold"><span>Balance</span><span>{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($balance, 2) }}</span></div>
            </div>

            <div class="text-xs mb-2">
                <div class="font-semibold mb-1">Payments</div>
                @forelse ($order->payments as $pay)
                    <div class="flex justify-between">
                        <span class="capitalize">{{ str_replace('_',' ', $pay->method) }} ({{ $pay->status }})</span>
                        <span>{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format($pay->amount, 2) }}</span>
                    </div>
                @empty
                    <div class="text-gray-500">No payments</div>
                @endforelse
            </div>

            <div class="text-center text-[10px] text-gray-600 whitespace-pre-line">
                {{ trim((function_exists('setting') ? setting('pos.receipt_footer','Thank you for your purchase!') : 'Thank you for your purchase!')) }}
            </div>
        </div>
    </div>

    <style>
        @media print {
            header, nav, aside { display: none !important; }
            main { padding: 0 !important; }
            .print\:w-80 { width: 80mm !important; }
        }
        /* Utility for previewing width on screen */
        @media screen {
            .thermal\:w-80 { width: 320px; } /* approx 80mm at 96dpi */
        }
    </style>
</x-app-layout>
