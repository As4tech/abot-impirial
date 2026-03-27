<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Room</h2>
    </x-slot>

    <div class="p-4 max-w-2xl">
        <form method="POST" action="{{ route('rooms.update', $room) }}" class="space-y-4 bg-white p-6 rounded shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">Room Number</label>
                <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('room_number')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Type</label>
                <input type="text" name="type" value="{{ old('type', $room->type) }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('type')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $room->price) }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('price')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                    @foreach(['Available','Occupied','Cleaning'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $room->status)===$s)>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded border">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
