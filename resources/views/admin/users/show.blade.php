<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Details</h2>
            <a href="{{ route('admin.users.roles.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Roles</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->roles->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-500">No roles assigned</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Joined</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at?->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-gray-800 text-white rounded">Edit User</a>
            </div>
        </div>
    </div>
</x-app-layout>
