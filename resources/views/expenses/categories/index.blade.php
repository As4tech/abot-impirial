<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Expense Categories</h2>
            @can('expenses.manage')
            <a href="{{ route('expenses.categories.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded">New Category</a>
            @endcan
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($list as $cat)
                        <tr>
                            <td class="px-4 py-2">{{ $cat->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $cat->description }}</td>
                            <td class="px-4 py-2 text-right">
                                @can('expenses.manage')
                                <a href="{{ route('expenses.categories.edit', $cat) }}" class="px-3 py-1 border rounded">Edit</a>
                                <form method="POST" action="{{ route('expenses.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 border rounded text-red-600">Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-6 text-gray-500" colspan="3">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $list->links() }}</div>
        </div>
    </div>
</x-app-layout>
