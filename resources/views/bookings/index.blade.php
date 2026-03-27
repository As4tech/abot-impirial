<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bookings</h2>
            <a href="{{ route('bookings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Check-in</a>
        </div>
    </x-slot>

    <div class="p-4">
        @if (session('status'))
            <div class="mb-3 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $b)
                        <tr>
                            <td class="px-4 py-2">{{ $b->id }}</td>
                            <td class="px-4 py-2">Room {{ $b->room->room_number }}</td>
                            <td class="px-4 py-2">{{ $b->guest->name }}</td>
                            <td class="px-4 py-2">{{ $b->check_in->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">{{ optional($b->check_out)?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($b->status) }}</td>
                            <td class="px-4 py-2 text-right">
                                @if($b->status === 'active')
                                    <form method="POST" action="{{ route('bookings.checkout', $b) }}">
                                        @csrf
                                        <button class="text-green-700 hover:underline">Check-out</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
