<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Categories</h2>
            <a href="{{ route('inventory.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Category</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search categories..." class="border rounded px-3 py-2 w-full max-w-sm" />
        </form>

        <div class="bg-white shadow-sm rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('inventory.categories.edit', $category) }}" title="Edit" class="inline-flex items-center text-blue-600 hover:underline">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M16.5 3.964a2.5 2.5 0 113.536 3.536L7.5 20.036H4v-3.5L16.5 3.964z"/></svg>
                                <span class="sr-only">Edit</span>
                            </a>
                            <form method="POST" action="{{ route('inventory.categories.destroy', $category) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button title="Delete" class="inline-flex items-center text-red-600 hover:underline" onclick="return confirm('Delete this category?')">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-7 0h8"/></svg>
                                    <span class="sr-only">Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-gray-500" colspan="2">No categories found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
