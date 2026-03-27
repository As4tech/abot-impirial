<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Check-in</h2>
    </x-slot>

    <div class="p-4 max-w-2xl">
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4 bg-white p-6 rounded shadow-sm">
            @csrf

            <div>
                <label class="block text-sm font-medium">Room</label>
                <select name="room_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- choose room --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">Room {{ $room->room_number }} ({{ $room->type }})</option>
                    @endforeach
                </select>
                @error('room_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Guest Name</label>
                <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('guest_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Guest Phone</label>
                <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" class="mt-1 w-full border rounded px-3 py-2" />
                @error('guest_phone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Check-in</button>
                <a href="{{ route('bookings.index') }}" class="px-4 py-2 rounded border">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
