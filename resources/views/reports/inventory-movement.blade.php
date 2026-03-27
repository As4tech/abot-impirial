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
            <div class="p-4 border-b font-semibold">Data</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Net Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($series as $row)
                            <tr>
                                <td class="px-4 py-2">{{ $row['date'] }}</td>
                                <td class="px-4 py-2">{{ number_format($row['net'], 3) }}</td>
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
