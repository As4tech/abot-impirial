<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Purchase</h2>
    </x-slot>

    <div class="p-4">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('inventory.purchases.store') }}" id="purchase-form" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Supplier</label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- none --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border divide-y divide-gray-200" id="items-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="add-item" class="mt-3 bg-gray-800 hover:bg-black text-white px-3 py-2 rounded">Add Item</button>
                    @error('items')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('inventory.purchases.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save Purchase</button>
                </div>
            </form>
        </div>
    </div>

    <template id="row-template">
        <tr>
            <td class="px-3 py-2">
                <select name="REPLACE[product_id]" class="w-64 border rounded px-2 py-1">
                    <option value="">-- choose product --</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2"><input name="REPLACE[quantity]" type="number" step="0.001" min="0.001" value="1" class="w-28 border rounded px-2 py-1" /></td>
            <td class="px-3 py-2"><input name="REPLACE[cost_price]" type="number" step="0.01" min="0" value="0" class="w-28 border rounded px-2 py-1" /></td>
            <td class="px-3 py-2 text-right"><button type="button" class="remove-row text-red-600 hover:underline">Remove</button></td>
        </tr>
    </template>

    <script>
        (function(){
            const body = document.getElementById('items-body');
            const add = document.getElementById('add-item');
            const tpl = document.getElementById('row-template');
            let i = 0;
            function addRow(){
                const html = tpl.innerHTML.replaceAll('REPLACE', `items[${i}]`);
                const tr = document.createElement('tbody');
                tr.innerHTML = html.trim();
                const row = tr.firstElementChild;
                body.appendChild(row);
                i++;
            }
            add?.addEventListener('click', addRow);
            body?.addEventListener('click', function(e){
                if (e.target && e.target.classList.contains('remove-row')){
                    e.target.closest('tr')?.remove();
                }
            });
            // add one initial row
            addRow();
        })();
    </script>
</x-app-layout>
