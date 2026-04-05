<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Register Sessions</h2>
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

        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-sm text-gray-500">Sessions Opened</div>
                <div class="text-2xl font-semibold">{{ $summary['opened'] }}</div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-sm text-gray-500">Sessions Closed</div>
                <div class="text-2xl font-semibold">{{ $summary['closed'] }}</div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-sm text-gray-500">Cash Collected</div>
                <div class="text-2xl font-semibold"><x-currency :amount="$summary['cash_collected']" /></div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-sm text-gray-500">Mobile Money</div>
                <div class="text-2xl font-semibold"><x-currency :amount="$summary['mobile_money_collected']" /></div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opened At</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opening Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed At</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closing Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cash</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile Money</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected Cash</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sessions as $s)
                        <tr>
                            <td class="px-4 py-3">{{ $s->user?->name ?? ('User #'.$s->user_id) }}</td>
                            <td class="px-4 py-3">{{ optional($s->opened_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3"><x-currency :amount="$s->opening_amount" /></td>
                            <td class="px-4 py-3">{{ optional($s->closed_at)->format('Y-m-d H:i') ?: '-' }}</td>
                            <td class="px-4 py-3">@if($s->closing_amount !== null)<x-currency :amount="$s->closing_amount" />@else - @endif</td>
                            <td class="px-4 py-3"><x-currency :amount="$s->cash_collected" /></td>
                            <td class="px-4 py-3"><x-currency :amount="$s->mobile_money_collected" /></td>
                            <td class="px-4 py-3"><x-currency :amount="$s->expected_cash_drawer" /></td>
                            <td class="px-4 py-3">@if($s->variance !== null)<x-currency :amount="$s->variance" />@else - @endif</td>
                            <td class="px-4 py-3">{{ ucfirst($s->status) }}</td>
                            <td class="px-4 py-3">{{ $s->notes }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="7">No register sessions found for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
