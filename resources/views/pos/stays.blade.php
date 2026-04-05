<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Active Stays</h2>
            <a href="{{ route('pos.index') }}" class="text-sm text-blue-700 hover:underline">Back to POS</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Checked in</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Elapsed</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Initial charge</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $b)
                        <tr>
                            <td class="px-4 py-2">Room {{ $b->room?->room_number }} <span class="text-gray-500">({{ $b->room?->type }})</span></td>
                            <td class="px-4 py-2 capitalize">{{ $b->rate_type ?? 'long' }}</td>
                            <td class="px-4 py-2">{{ $b->check_in_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">{{ ucfirst($b->rate_type ?? 'long') }}</td>
                            <td class="px-4 py-2 text-right"><x-currency :amount="$b->initial_charge ?? 0" /></td>
                            <td class="px-4 py-2">#{{ $b->order?->id }}</td>
                            <td class="px-4 py-2 text-right">
                                <form method="POST" action="{{ route('bookings.checkout', $b) }}">
                                    @csrf
                                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded" type="submit">Check-out</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-gray-500">No active stays.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
