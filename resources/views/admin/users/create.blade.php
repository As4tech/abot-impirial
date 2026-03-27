<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create User</h2>
            <a href="{{ route('admin.users.roles.index') }}" class="px-4 py-2 border rounded">Back</a>
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
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Assign Roles</label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center gap-2 border rounded px-2 py-1">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" />
                                <span class="text-sm">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Create User</button>
            </form>
        </div>
    </div>
</x-app-layout>
