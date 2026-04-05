<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Profit</h2>
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
            <canvas id="productProfitChart" height="80"></canvas>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-3 font-semibold">Per-Product Profit</div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Sold</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($details as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row->name }}</td>
                            <td class="px-4 py-3">{{ (int) $row->total_qty }}</td>
                            <td class="px-4 py-3"><x-currency :amount="$row->total_revenue" /></td>
                            <td class="px-4 py-3"><x-currency :amount="$row->total_cost" /></td>
                            <td class="px-4 py-3"><x-currency :amount="$row->total_profit" /></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="5">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const series = @json($series);
        const labels = series.map(s => s.date);
        const revenue = series.map(s => s.revenue);
        const cost = series.map(s => s.cost);
        const profit = series.map(s => s.profit);
        const ctx = document.getElementById('productProfitChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Revenue', data: revenue, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', yAxisID: 'y' },
                    { label: 'Cost', data: cost, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.2)', yAxisID: 'y' },
                    { label: 'Profit', data: profit, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.2)', yAxisID: 'y' },
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                stacked: false,
                scales: {
                    y: { type: 'linear', position: 'left' }
                }
            }
        });
    </script>
</x-app-layout>
