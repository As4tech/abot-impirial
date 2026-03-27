<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permission Matrix</h2>
            <div class="text-sm text-gray-500">Manage role-permission assignments</div>
        </div>
    </x-slot>

    <div class="p-6 space-y-5">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        @can('manage-permissions')
        <form method="POST" action="{{ route('admin.permissions.store') }}" class="bg-white shadow-sm rounded-lg p-4">
            @csrf
            <label class="block text-sm font-medium mb-1">New Permission</label>
            <div class="flex gap-2">
                <input type="text" name="name" class="flex-1 border rounded px-3 py-2" placeholder="e.g. expenses.delete" required>
                <button class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded">Add</button>
            </div>
            <p class="text-xs text-gray-500 mt-2">Tip: Use module.action format (e.g., expenses.view, expenses.create).</p>
        </form>
        @endcan

        <form method="POST" action="{{ route('admin.permissions.matrix.update') }}" id="perm-matrix-form">
            @csrf

            <div class="flex items-center gap-3 mb-3">
                <button type="button" id="selectAll" class="px-3 py-1.5 border rounded">Select all</button>
                <button type="button" id="clearAll" class="px-3 py-1.5 border rounded">Clear all</button>
                <button type="submit" id="saveBtn" class="ml-auto bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded">Save Changes</button>
            </div>

            @foreach($grouped as $module => $perms)
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-3 border-b flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4m0 6l9 4 9-4"/></svg>
                        <div class="font-semibold">{{ $module }}</div>
                        <button type="button" class="ml-auto text-sm text-blue-700 hover:underline group-toggle" data-target="module-{{ Str::slug($module) }}">Toggle module</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 text-left w-64">Permission</th>
                                    @foreach($roles as $role)
                                        <th class="px-4 py-2 text-center">{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="module-{{ Str::slug($module) }}">
                                @foreach($perms as $perm)
                                    <tr>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-700">{{ $perm->name }}</td>
                                        @foreach($roles as $role)
                                            <td class="px-4 py-2 text-center">
                                                @php($assigned = isset($rolePermissions[$role->id][$perm->name]))
                                                <input type="checkbox"
                                                    class="perm-checkbox h-4 w-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900"
                                                    name="assign[{{ $role->id }}][]"
                                                    value="{{ $perm->name }}"
                                                    @checked($assigned)
                                                />
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <div class="flex items-center justify-end">
                <button type="submit" id="saveBtnBottom" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        (function(){
            const form = document.getElementById('perm-matrix-form');
            const selectAll = document.getElementById('selectAll');
            const clearAll = document.getElementById('clearAll');

            selectAll?.addEventListener('click', () => {
                form.querySelectorAll('input.perm-checkbox').forEach(cb => cb.checked = true);
            });
            clearAll?.addEventListener('click', () => {
                form.querySelectorAll('input.perm-checkbox').forEach(cb => cb.checked = false);
            });
            document.querySelectorAll('.group-toggle').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-target');
                    const rows = document.getElementById(id);
                    if (!rows) return;
                    const boxes = rows.querySelectorAll('input.perm-checkbox');
                    const allChecked = Array.from(boxes).every(cb => cb.checked);
                    boxes.forEach(cb => cb.checked = !allChecked);
                });
            });
        })();
    </script>
</x-app-layout>
