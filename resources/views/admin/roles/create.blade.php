<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Role</h2>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium">Permissions</label>
                        <button type="button" class="text-sm underline" onclick="toggleAll(true)">Select all</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        @foreach($permissions as $perm)
                            <label class="inline-flex items-center gap-2 border rounded px-2 py-1">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" />
                                <span class="text-sm">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Create Role</button>
            </form>
        </div>
    </div>

    <script>
        function toggleAll(state){
            document.querySelectorAll("input[name='permissions[]']").forEach(cb=>cb.checked=!!state);
        }
    </script>
</x-app-layout>
