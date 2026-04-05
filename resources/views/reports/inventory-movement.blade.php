<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Movement (Last {{ $days }} days)</h2>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <canvas id="movementChart" height="120"></canvas>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4 border-b font-semibold">Detailed Movements</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($movements as $movement)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-4 py-2 text-sm font-medium">{{ $movement->product?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">
                                    @if($movement->type === 'in')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                            </svg>
                                            IN
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                            </svg>
                                            OUT
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm font-mono">{{ number_format($movement->quantity, 3) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    @if($movement->reference)
                                        {{ $movement->reference }}
                                    @else
                                        Manual
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No movements found in the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-t">
                {{ $movements->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('movementChart');
            if (!ctx) return;
            const labels = {!! json_encode(array_column($series, 'date')) !!};
            const data = {!! json_encode(array_map(fn($r) => (float) $r['net'], $series)) !!};
            new Chart(ctx, {
                type: 'bar',
                data: { labels, datasets: [{ label: 'Net Qty', data, backgroundColor: '#10b981' }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        })();
    </script>
</x-app-layout>
