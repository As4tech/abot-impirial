<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Activity (Last {{ $days }} days)</h2>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        <form method="get" action="{{ route('reports.bookings') }}" class="bg-white shadow-sm rounded-lg p-4 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Days</label>
                <input type="number" name="days" min="1" max="365" value="{{ $days }}" class="w-full border rounded px-2 py-1" />
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
            <div class="md:col-span-2">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <canvas id="bookingsChart" height="120"></canvas>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4 border-b font-semibold">Data</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check-ins</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $totalCheckins = 0;
                            $totalRevenue = 0.0;
                        @endphp
                        @foreach ($series as $row)
                            @php
                                $totalCheckins += (int) $row['checkins'];
                                $totalRevenue += (float) $row['revenue'];
                            @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $row['date'] }}</td>
                                <td class="px-4 py-2">{{ $row['checkins'] }}</td>
                                <td class="px-4 py-2"><x-currency :amount="$row['revenue']" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Totals</th>
                            <th class="px-4 py-2 text-left">{{ $totalCheckins }}</th>
                            <th class="px-4 py-2 text-left"><x-currency :amount="$totalRevenue" /></th>
                        </tr>
                    </tfoot>
                </table>
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $sumAmount = 0.0;
                        @endphp
                        @foreach(($details ?? []) as $b)
                            @php
                                $amount = (float) ($b->computed_charge ?? $b->initial_charge ?? 0);
                                $sumAmount += $amount;
                            @endphp
                            <tr>
                                <td class="px-4 py-2">Room {{ optional($b->room)->room_number ?? $b->room_id }}</td>
                                <td class="px-4 py-2">{{ optional($b->check_in_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2">{{ optional($b->check_out_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                <td class="px-4 py-2"><x-currency :amount="$amount" /></td>
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
