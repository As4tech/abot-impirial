<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rooms</h2>
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 4.5a.75.75 0 0 1 .75.75v6h6a.75.75 0 0 1 0 1.5h-6v6a.75.75 0 0 1-1.5 0v-6h-6a.75.75 0 0 1 0-1.5h6v-6A.75.75 0 0 1 12 4.5Z"/></svg>
                New Room
            </a>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="mb-3 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <form method="GET" class="bg-white rounded-lg shadow-sm p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Search</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Room # or type" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        @foreach(['Available','Occupied','Cleaning'] as $s)
                            <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded border">Reset</a>
                </div>
            </div>
        </form>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                @if($room->image_path)
                                    <img src="{{ $room->image_path }}" alt="{{ $room->room_number }}" class="h-12 w-12 object-cover rounded border" />
                                @else
                                    <div class="h-12 w-12 rounded border bg-gray-50 flex items-center justify-center text-gray-400 text-xs">N/A</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $room->room_number }}</td>
                            <td class="px-4 py-3">{{ $room->type }}</td>
                            <td class="px-4 py-3"><x-currency :amount="$room->price" /></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs rounded {{
                                    $room->status==='Available' ? 'bg-green-100 text-green-800' : (
                                    $room->status==='Occupied' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800')
                                }}">{{ $room->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('rooms.edit', $room) }}" title="Edit" class="inline-flex items-center text-blue-600 hover:underline">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M16.5 3.964a2.5 2.5 0 113.536 3.536L7.5 20.036H4v-3.5L16.5 3.964z"/></svg>
                                    <span class="sr-only">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Delete" class="inline-flex items-center text-red-600 hover:underline" onclick="return confirm('Delete this room?')">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-7 0h8"/></svg>
                                        <span class="sr-only">Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10">
                                <div class="text-center text-gray-600">
                                    <p class="mb-3">No rooms found.</p>
                                    <a href="{{ route('rooms.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 4.5a.75.75 0 0 1 .75.75v6h6a.75.75 0 0 1 0 1.5h-6v6a.75.75 0 0 1-1.5 0v-6h-6a.75.75 0 0 1 0-1.5h6v-6A.75.75 0 0 1 12 4.5Z"/></svg>
                                        Create your first room
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $rooms->links() }}</div>
    </div>
</x-app-layout>
