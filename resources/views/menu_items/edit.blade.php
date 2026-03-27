<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Menu Item</h2>
    </x-slot>

    <div class="p-4 max-w-xl">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('menu-items.update', $item) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}" class="w-full border rounded px-3 py-2" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $item->price) }}" class="w-full border rounded px-3 py-2" />
                    @error('price')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('menu-items.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
