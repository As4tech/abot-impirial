<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Expense Category</h2>
            <a href="{{ route('expenses.categories.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('expenses.categories.store') }}" class="space-y-4 max-w-xl">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>
                <button class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded">Create</button>
            </form>
        </div>
    </div>
</x-app-layout>
