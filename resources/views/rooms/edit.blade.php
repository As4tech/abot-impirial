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
                <label class="block text-sm font-medium">Room Type</label>
                <select name="room_type_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">Select room type</option>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}" @selected((string) old('room_type_id', $room->room_type_id) === (string) $roomType->id)>{{ $roomType->name }}</option>
                    @endforeach
                </select>
                @error('room_type_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Stay Option</label>
                <select name="stay_type" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="long" @selected(old('stay_type', $room->stay_type ?? 'long') === 'long')>Long</option>
                    <option value="short" @selected(old('stay_type', $room->stay_type) === 'short')>Short</option>
                </select>
                @error('stay_type')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Long Price</label>
                <input type="number" step="0.01" name="long_price" value="{{ old('long_price', $room->long_price ?? $room->price) }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('long_price')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Short Price</label>
                <input type="number" step="0.01" name="short_price" value="{{ old('short_price', $room->short_price ?? $room->price) }}" class="mt-1 w-full border rounded px-3 py-2" required />
                @error('short_price')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Room Features</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_ac" value="1" {{ old('has_ac', $room->has_ac) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">AC</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_fan" value="1" {{ old('has_fan', $room->has_fan) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Fan</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_tv" value="1" {{ old('has_tv', $room->has_tv) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">TV</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_fridge" value="1" {{ old('has_fridge', $room->has_fridge) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Fridge</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Bed Type</label>
                <select name="bed_type" class="mt-1 w-full border rounded px-3 py-2">
                    <option value="">-- Select --</option>
                    <option value="Single" {{ old('bed_type', $room->bed_type) === 'Single' ? 'selected' : '' }}>Single</option>
                    <option value="Double" {{ old('bed_type', $room->bed_type) === 'Double' ? 'selected' : '' }}>Double</option>
                    <option value="Queen" {{ old('bed_type', $room->bed_type) === 'Queen' ? 'selected' : '' }}>Queen</option>
                    <option value="King" {{ old('bed_type', $room->bed_type) === 'King' ? 'selected' : '' }}>King</option>
                </select>
                @error('bed_type')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
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
