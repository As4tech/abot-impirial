<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-5 flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Today's Expenses</div>
                    <div class="mt-1 text-3xl font-bold"><x-currency :amount="$todaysExpenses ?? 0" /></div>
                </div>
                <div class="h-10 w-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zm1 5v4.59l3.3 3.3-1.42 1.42L11 13.41V8h2z"/></svg>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5 flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Profit Today</div>
                    <div class="mt-1 text-3xl font-bold"><x-currency :amount="$profitToday ?? 0" /></div>
                </div>
                <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 3l9 4.5-9 4.5-9-4.5L12 3zm0 7.5l9 4.5-9 4.5-9-4.5 9-4.5z"/></svg>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-5 flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Today's Sales</div>
                    <div class="mt-1 text-3xl font-bold"><x-currency :amount="$todaysSales ?? 0" /></div>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M3 3h18v2H3zM5 7h14v2H5zM3 11h18v2H3zM5 15h14v2H5zM3 19h18v2H3z"/></svg>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5 flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Orders Today</div>
                    <div class="mt-1 text-3xl font-bold">{{ (int) ($todaysOrders ?? 0) }}</div>
                </div>
                <div class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M7 4h10l1 3h3v2h-2l-2 10H7L5 9H3V7h3l1-3zm2.2 5 1.2 8h6.2l1.6-8H9.2z"/></svg>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5 flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-gray-500">Avg Order Value</div>
                    <div class="mt-1 text-3xl font-bold"><x-currency :amount="$avgOrder ?? 0" /></div>
                </div>
                <div class="h-10 w-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 3l9 4.5-9 4.5-9-4.5L12 3zm0 7.5 9 4.5-9 4.5-9-4.5 9-4.5z"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-5 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-semibold">Revenue Breakdown (Today)</div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#3b82f6"></span>Restaurant</span>
                        <span class="inline-flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#10b981"></span>Room</span>
                        <span class="inline-flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#f59e0b"></span>Products</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 items-center">
                    <div class="md:col-span-2 flex items-center justify-center">
                        <canvas id="breakdownChart" class="w-full max-w-md"></canvas>
                    </div>
                    <div class="md:col-span-1 mt-4 md:mt-0 space-y-3 text-sm">
                        <div class="flex items-center justify-between border rounded p-2">
                            <div class="flex items-center gap-2"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#3b82f6"></span>Restaurant</div>
                            <div class="text-right">
                                <div class="font-semibold"><x-currency :amount="$breakdown['restaurant'] ?? 0" /></div>
                                <div class="text-gray-500">{{ number_format((($breakdown['restaurant'] ?? 0)/($breakdownTotal ?? 1))*100, 0) }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border rounded p-2">
                            <div class="flex items-center gap-2"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#10b981"></span>Room service</div>
                            <div class="text-right">
                                <div class="font-semibold"><x-currency :amount="$breakdown['room'] ?? 0" /></div>
                                <div class="text-gray-500">{{ number_format((($breakdown['room'] ?? 0)/($breakdownTotal ?? 1))*100, 0) }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border rounded p-2">
                            <div class="flex items-center gap-2"><span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#f59e0b"></span>Products</div>
                            <div class="text-right">
                                <div class="font-semibold"><x-currency :amount="$breakdown['products'] ?? 0" /></div>
                                <div class="text-gray-500">{{ number_format((($breakdown['products'] ?? 0)/($breakdownTotal ?? 1))*100, 0) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-5 border-b">
                    <div class="font-semibold">Low Stock Alerts</div>
                    <div class="text-xs text-gray-500">Items at or below threshold</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-right">Stock</th>
                                <th class="px-4 py-2 text-left">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($lowStock ?? [] as $p)
                                <tr>
                                    <td class="px-4 py-2">{{ $p->name }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ ($p->stock_quantity ?? 0) <= (int) config('inventory.threshold',5) ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700' }}">{{ number_format($p->stock_quantity, 3) }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ $p->unit }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-4 text-gray-500" colspan="3">No low stock items.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('breakdownChart');
            if (!ctx) return;
            const data = {
                labels: ['Restaurant','Room','Products'],
                datasets: [{
                    data: [
                        {{ (float) ($breakdown['restaurant'] ?? 0) }},
                        {{ (float) ($breakdown['room'] ?? 0) }},
                        {{ (float) ($breakdown['products'] ?? 0) }}
                    ],
                    backgroundColor: ['#3b82f6','#10b981','#f59e0b'],
                    borderWidth: 0,
                }]
            };
            const total = {{ (float) ($breakdownTotal ?? 0) }};
            const currency = '{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }}';
            const centerText = {
                id: 'centerText',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    const {ctx, chartArea:{width, height}} = chart;
                    ctx.save();
                    ctx.font = '600 16px Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
                    ctx.fillStyle = '#111827';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(currency + ' ' + new Intl.NumberFormat('en-PH', {minimumFractionDigits:2}).format(total), chart.getDatasetMeta(0).data[0].x, chart.getDatasetMeta(0).data[0].y);
                    ctx.restore();
                }
            };
            new Chart(ctx, {
                type: 'doughnut',
                data,
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: { legend: { display: false } },
                    layout: { padding: 10 },
                    animation: { animateRotate: true, duration: 600 }
                },
                plugins: [centerText]
            });
        })();
    </script>
</x-app-layout>
