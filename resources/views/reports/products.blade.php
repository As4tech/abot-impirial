<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Sales</h2>
    </x-slot>

    <div class="p-4 space-y-6">
        <form method="GET" class="flex items-end gap-3">
            <div>
                <label class="block text-sm font-medium">Days</label>
                <input type="number" min="1" max="180" name="days" value="{{ $days }}" class="w-24 border rounded px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm font-medium">From</label>
                <input type="date" name="from" value="{{ optional($from)->toDateString() }}" class="border rounded px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm font-medium">To</label>
                <input type="date" name="to" value="{{ optional($to)->toDateString() }}" class="border rounded px-3 py-2" />
            </div>
            <button class="h-10 px-4 rounded bg-gray-800 text-white">Apply</button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="h-10 px-4 rounded border inline-flex items-center">Export CSV</a>
        </form>

        <div class="bg-white shadow-sm rounded-lg p-4">
            <canvas id="productsChart" height="80"></canvas>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-3 font-semibold">Top Products</div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Sold</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($details as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row->name }}</td>
                            <td class="px-4 py-3">{{ (int) $row->total_qty }}</td>
                            <td class="px-4 py-3"><x-currency :amount="$row->total_revenue" /></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="3">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const series = @json($series);
        const labels = series.map(s => s.date);
        const items = series.map(s => s.items);
        const revenue = series.map(s => s.revenue);
        const ctx = document.getElementById('productsChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Items', data: items, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)', yAxisID: 'y' },
                    { label: 'Revenue', data: revenue, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', yAxisID: 'y1' },
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                stacked: false,
                scales: {
                    y: { type: 'linear', position: 'left' },
                    y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false } }
                }
            }
        });
    </script>
</x-app-layout>
