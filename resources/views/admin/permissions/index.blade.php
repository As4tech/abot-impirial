<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permissions</h2>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border rounded">Manage Roles</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.permissions.store') }}" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium">New Permission</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="e.g. manage_inventory" required />
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Add</button>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($permissions as $perm)
                        <tr>
                            <td class="px-4 py-2">{{ $perm->name }}</td>
                            <td class="px-4 py-2 text-right">
                                <form action="{{ route('admin.permissions.destroy', $perm) }}" method="POST" onsubmit="return confirm('Delete this permission?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 border rounded text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-4 text-gray-500" colspan="2">No permissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
