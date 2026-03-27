<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Expenses</h2>
            <div class="space-x-2">
                @can('expenses.manage')
                <a href="{{ route('expenses.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded">Record Expense</a>
                @endcan
                @can('expenses.manage')
                <a href="{{ route('expenses.categories.index') }}" class="px-4 py-2 border rounded">Categories</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-5">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">Today's Expenses</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$summary['today'] ?? 0" /></div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">This Week</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$summary['week'] ?? 0" /></div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">This Month</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$summary['month'] ?? 0" /></div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium">From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        @foreach($cats as $c)
                            <option value="{{ $c->id }}" @selected(($filters['category_id'] ?? '')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Method</label>
                    <select name="payment_method" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        <option value="cash" @selected(($filters['payment_method'] ?? '')==='cash')>Cash</option>
                        <option value="momo" @selected(($filters['payment_method'] ?? '')==='momo')>Mobile Money</option>
                        <option value="bank" @selected(($filters['payment_method'] ?? '')==='bank')>Bank</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('expenses.export', request()->only(['date_from','date_to','category_id','payment_method'])) }}" class="px-4 py-2 border rounded">CSV</a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold">Expenses by Category</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                <div class="flex items-center justify-center"><canvas id="expenseBreakdown" class="w-full max-w-md"></canvas></div>
                <div class="space-y-2 text-sm">
                    @forelse($cats as $c)
                        @php $val = (float) ($breakdown[$c->id] ?? 0); @endphp
                        @if($val>0)
                        <div class="flex items-center justify-between border rounded p-2">
                            <div>{{ $c->name }}</div>
                            <div class="font-semibold"><x-currency :amount="$val" /></div>
                        </div>
                        @endif
                    @empty
                        <div class="text-gray-500">No categories.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-right">Amount</th>
                        <th class="px-4 py-2 text-left">Method</th>
                        <th class="px-4 py-2 text-left">Recorded by</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($list as $e)
                        <tr>
                            <td class="px-4 py-2">{{ $e->expense_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $e->category?->name }}</td>
                            <td class="px-4 py-2">{{ $e->title }}</td>
                            <td class="px-4 py-2 text-right"><x-currency :amount="$e->amount" /></td>
                            <td class="px-4 py-2 capitalize">{{ str_replace('_',' ', $e->payment_method) }}</td>
                            <td class="px-4 py-2">{{ $e->creator?->name }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-6 text-gray-500" colspan="6">No expenses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $list->links() }}</div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            const el = document.getElementById('expenseBreakdown');
            if (!el) return;
            const labels = {!! json_encode($breakdownLabels ?? []) !!};
            const data = {!! json_encode($breakdownData ?? []) !!};
            if (labels.length === 0) return;
            new Chart(el, {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#84cc16','#f97316'] }] },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        })();
    </script>
</x-app-layout>
