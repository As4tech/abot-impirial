<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Supplier</h2>
            <a href="{{ route('inventory.suppliers.index') }}" class="px-4 py-2 border rounded">Back to list</a>
        </div>
    </x-slot>

    <div class="p-4">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6 max-w-xl">
            <form method="POST" action="{{ route('inventory.suppliers.update', $supplier) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $supplier->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                    <textarea id="contact" name="contact" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('contact', $supplier->contact) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Supplier</button>
                    <a href="{{ route('inventory.suppliers.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
