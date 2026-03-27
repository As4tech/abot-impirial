<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
            <div class="space-x-2">
                <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">New Role</a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        <tr>
                            <td class="px-4 py-2">{{ $role->name }}</td>
                            <td class="px-4 py-2">{{ $role->permissions_count }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="px-3 py-1 border rounded">Edit</a>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 border rounded text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-4 text-gray-500" colspan="3">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
