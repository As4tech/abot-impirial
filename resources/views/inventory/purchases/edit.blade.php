<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Purchase #{{ $purchase->id }}</h2>
    </x-slot>

    <div class="p-4">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('inventory.purchases.update', $purchase) }}" id="purchase-form" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Supplier</label>
                        <select name="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- none --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $purchase->supplier_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
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
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="divide-y divide-gray-100">
                                @foreach ($purchase->items as $index => $item)
                                    <tr data-index="{{ $index }}">
                                        <td class="px-3 py-2">
                                            <select name="items[{{ $index }}][product_id]" class="w-64 border rounded px-2 py-1" data-product-id="{{ $item->product_id }}">
                                                <option value="">-- choose product --</option>
                                                @foreach ($products as $p)
                                                    <option value="{{ $p->id }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2"><input name="items[{{ $index }}][quantity]" type="number" step="0.001" min="0.001" value="{{ $item->quantity }}" class="w-28 border rounded px-2 py-1" /></td>
                                        <td class="px-3 py-2"><input name="items[{{ $index }}][cost_price]" type="number" step="0.01" min="0" value="{{ $item->cost_price }}" class="w-28 border rounded px-2 py-1" /></td>
                                        <td class="px-3 py-2 text-right"><button type="button" class="remove-row text-red-600 hover:underline">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="add-item" class="mt-3 bg-gray-800 hover:bg-black text-white px-3 py-2 rounded">Add Item</button>
                    @error('items')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('inventory.purchases.show', $purchase) }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update Purchase</button>
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
            
            function getNextIndex() {
                const rows = body.querySelectorAll('tr');
                let maxIndex = -1;
                rows.forEach(row => {
                    const selects = row.querySelectorAll('select[name^="items["]');
                    selects.forEach(select => {
                        const match = select.name.match(/items\[(\d+)\]/);
                        if (match) {
                            const index = parseInt(match[1]);
                            if (index > maxIndex) {
                                maxIndex = index;
                            }
                        }
                    });
                });
                return maxIndex + 1;
            }
            
            function addRow(){
                const nextIndex = getNextIndex();
                const html = tpl.innerHTML.replaceAll('REPLACE', `items[${nextIndex}]`);
                const tr = document.createElement('tbody');
                tr.innerHTML = html.trim();
                const row = tr.firstElementChild;
                body.appendChild(row);
            }
            
            add?.addEventListener('click', addRow);
            body?.addEventListener('click', function(e){
                if (e.target && e.target.classList.contains('remove-row')){
                    e.target.closest('tr')?.remove();
                }
            });
        })();
    </script>
</x-app-layout>
