<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Activity Report</h2>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        <form method="get" action="{{ route('reports.bookings') }}" class="bg-white shadow-sm rounded-lg p-4 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-600 mb-1">From Date</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="w-full border rounded px-2 py-1" />
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">To Date</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="w-full border rounded px-2 py-1" />
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border rounded px-2 py-1">
                    <option value="" {{ $status==='' ? 'selected' : '' }}>Any</option>
                    <option value="active" {{ $status==='active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $status==='completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Room</label>
                <select name="room_id" class="w-full border rounded px-2 py-1">
                    <option value="">All Rooms</option>
                    @foreach(($rooms ?? []) as $r)
                        <option value="{{ $r->id }}" {{ (int)($roomId ?? 0) === (int)$r->id ? 'selected' : '' }}>Room {{ $r->room_number }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 border rounded bg-gray-50">Apply</button>
            </div>
        </form>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Check-ins</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $checkinsCount }}</p>
                        <p class="text-sm text-gray-600">Total check-ins in period</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900"><x-currency :amount="$revenueTotal" /></p>
                        <p class="text-sm text-gray-600">Total paid revenue in period</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <canvas id="bookingsChart" height="120"></canvas>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4 border-b font-semibold">Detailed Bookings</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Paid Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $sumAmount = 0.0;
                        @endphp
                        @foreach(($details ?? []) as $b)
                            @php
                                // Calculate actual paid amount from payments
                                $paidAmount = 0.0;
                                if ($b->order && $b->order->payments) {
                                    $paidAmount = (float) $b->order->payments->where('status', 'paid')->sum('amount');
                                }
                                $sumAmount += $paidAmount;
                            @endphp
                            <tr>
                                <td class="px-4 py-2">Room {{ optional($b->room)->room_number ?? $b->room_id }}</td>
                                <td class="px-4 py-2">{{ optional($b->check_in_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2">{{ optional($b->check_out_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                <td class="px-4 py-2"><x-currency :amount="$paidAmount" /></td>
                                <td class="px-4 py-2">{{ ucfirst($b->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left" colspan="3">Total</th>
                            <th class="px-4 py-2 text-left"><x-currency :amount="$sumAmount" /></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            const labels = {!! json_encode(array_column($series, 'date')) !!};
            const bookings = {!! json_encode(array_map(fn($r) => (int) $r['checkins'], $series)) !!};
            const revenue = {!! json_encode(array_map(fn($r) => (float) $r['revenue'], $series)) !!};
            const currency = '{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }}';

            const el1 = document.getElementById('bookingsChart');
            if (el1) new Chart(el1, {
                type: 'bar',
                data: { labels, datasets: [{ label: 'Check-ins', data: bookings, backgroundColor: '#6366f1' }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });

            const el2 = document.getElementById('revenueChart');
            if (el2) new Chart(el2, {
                type: 'line',
                data: { labels, datasets: [{ label: 'Revenue ('+currency+')', data: revenue, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.15)', fill: true, tension: 0.25 }] },
                options: { }
            });
        })();
    </script>
</x-app-layout>
