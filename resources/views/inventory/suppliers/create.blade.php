<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Supplier</h2>
    </x-slot>

    <div class="p-4 max-w-xl">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('inventory.suppliers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" />
                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Contact</label>
                    <input type="text" name="contact" value="{{ old('contact') }}" class="w-full border rounded px-3 py-2" />
                    @error('contact')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('inventory.suppliers.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
