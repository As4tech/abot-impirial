<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Room Type Details</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <a href="{{ route('room-types.index') }}" class="text-indigo-600 hover:text-indigo-800">← Back to Room Types</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $roomType->name }}</h3>
                            
                            @if($roomType->image_url)
                                <img src="{{ $roomType->image_url }}" alt="{{ $roomType->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @endif

                            @if($roomType->description)
                                <p class="text-gray-600 mb-4">{{ $roomType->description }}</p>
                            @endif

                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Base Price:</dt>
                                    <dd class="text-sm text-gray-900"><x-currency :amount="$roomType->base_price" /></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Capacity:</dt>
                                    <dd class="text-sm text-gray-900">{{ $roomType->capacity }} guests</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd>
                                        @if($roomType->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            @if($roomType->amenities && count($roomType->amenities) > 0)
                                <h4 class="text-md font-medium text-gray-900 mb-3">Amenities</h4>
                                <ul class="space-y-2">
                                    @foreach($roomType->amenities as $amenity)
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $amenity }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">No amenities specified</p>
                            @endif
                        </div>
                    </div>

                    @if($roomType->rooms->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Rooms ({{ $roomType->rooms->count() }})</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Number</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($roomType->rooms as $room)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $room->room_number }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><x-currency :amount="$room->price" /></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @switch($room->status)
                                                        @case('Available')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                                            @break
                                                        @case('Occupied')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Occupied</span>
                                                            @break
                                                        @case('Cleaning')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Cleaning</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 flex items-center justify-end space-x-3">
                        @can('room-types.edit')
                            <a href="{{ route('room-types.edit', $roomType) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Edit Room Type
                            </a>
                        @endcan
                        @can('room-types.delete')
                            @if(!$roomType->rooms()->exists())
                                <form action="{{ route('room-types.destroy', $roomType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this room type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Delete Room Type
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
