<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Room Type</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('room-types.update', $roomType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" value="{{ old('name', $roomType->name) }}" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="base_price" :value="__('Base Price')" />
                                <x-text-input id="base_price" name="base_price" type="number" step="0.01" min="0" class="block mt-1 w-full" value="{{ old('base_price', $roomType->base_price) }}" required />
                                <x-input-error :messages="$errors->get('base_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="capacity" :value="__('Capacity')" />
                                <x-text-input id="capacity" name="capacity" type="number" min="1" class="block mt-1 w-full" value="{{ old('capacity', $roomType->capacity) }}" required />
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="image_url" :value="__('Image URL')" />
                                <x-text-input id="image_url" name="image_url" type="url" class="block mt-1 w-full" value="{{ old('image_url', $roomType->image_url) }}" />
                                <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('description', $roomType->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="amenities" :value="__('Amenities')" />
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter amenity and press Add" id="amenity-input">
                                        <button type="button" onclick="addAmenity()" class="ml-2 px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Add</button>
                                    </div>
                                    <div id="amenities-list" class="space-y-1">
                                        @foreach(old('amenities', $roomType->amenities ?? []) as $amenity)
                                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                <input type="hidden" name="amenities[]" value="{{ $amenity }}">
                                                <span>{{ $amenity }}</span>
                                                <button type="button" onclick="removeAmenity(this)" class="text-red-600 hover:text-red-800">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('amenities')" class="mt-2" />
                                <x-input-error :messages="$errors->get('amenities.*')" class="mt-2" />
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $roomType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('room-types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <x-primary-button>
                                Update Room Type
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addAmenity() {
            const input = document.getElementById('amenity-input');
            const list = document.getElementById('amenities-list');
            
            if (input.value.trim()) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
                div.innerHTML = `
                    <input type="hidden" name="amenities[]" value="${input.value}">
                    <span>${input.value}</span>
                    <button type="button" onclick="removeAmenity(this)" class="text-red-600 hover:text-red-800">×</button>
                `;
                list.appendChild(div);
                input.value = '';
            }
        }

        function removeAmenity(button) {
            button.parentElement.remove();
        }

        document.getElementById('amenity-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addAmenity();
            }
        });
    </script>
</x-app-layout>
