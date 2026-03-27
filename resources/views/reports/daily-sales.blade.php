<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daily Sales (Last {{ $days }} days)</h2>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <canvas id="salesChart" height="120"></canvas>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4 border-b font-semibold">Data</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($series as $row)
                            <tr>
                                <td class="px-4 py-2">{{ $row['date'] }}</td>
                                <td class="px-4 py-2"><x-currency :amount="$row['total']" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;
            const labels = {!! json_encode(array_column($series, 'date')) !!};
            const currency = '{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }}';
            const data = {!! json_encode(array_map(fn($r) => (float) $r['total'], $series)) !!};
            const nf = new Intl.NumberFormat(undefined, { notation: 'compact', maximumFractionDigits: 1 });
            const fmt = (v) => `${currency} ${nf.format(Number(v) || 0)}`;
            new Chart(ctx, {
                type: 'line',
                data: { labels, datasets: [{ label: 'Sales ('+currency+')', data, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.15)', fill: true, tension: 0.25 }] },
                options: {
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx)=> fmt(ctx.parsed.y) } } },
                    scales: { y: { beginAtZero: true, ticks: { callback: (v)=> fmt(v) } } }
                }
            });
        })();
    </script>
</x-app-layout>
