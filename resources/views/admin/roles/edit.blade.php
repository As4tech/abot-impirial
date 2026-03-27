<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Role: {{ $role->name }}</h2>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium">Permissions</label>
                        <div class="space-x-2 text-sm">
                            <button type="button" class="underline" onclick="toggleAll(true)">Select all</button>
                            <button type="button" class="underline" onclick="toggleAll(false)">Clear</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        @foreach($permissions as $perm)
                            <label class="inline-flex items-center gap-2 border rounded px-2 py-1">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, $assigned) ? 'checked' : '' }} />
                                <span class="text-sm">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function toggleAll(state){
            document.querySelectorAll("input[name='permissions[]']").forEach(cb=>cb.checked=!!state);
        }
    </script>
</x-app-layout>
