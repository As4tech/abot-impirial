<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assign Roles to Users</h2>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">New User</a>
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
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.users.roles.update', $user) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select multiple name="roles[]" size="1" class="border rounded px-2 py-1 md:min-w-[260px]" onfocus="this.size=4" onblur="this.size=1" onchange="this.form.submit()">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->roles->pluck('name')->contains($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="hidden md:inline px-3 py-1 border rounded">Save</button>
                                </form>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <span class="text-xs text-gray-500">Auto-saves on change</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
