<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
    </x-slot>

    <div class="p-4 max-w-2xl">
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="POST" action="{{ route('inventory.products.update', $product) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded px-3 py-2" />
                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- none --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" placeholder="e.g. bottle, crate, kg" class="w-full border rounded px-3 py-2" />
                    @error('unit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Stock</label>
                    <input type="number" step="0.001" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full border rounded px-3 py-2" />
                    @error('stock_quantity')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="w-full border rounded px-3 py-2" />
                    @error('cost_price')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? $product->price) }}" class="w-full border rounded px-3 py-2" />
                    @error('selling_price')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2" />
                    @error('image')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    @if($product->image_path)
                        <div class="mt-2 text-sm text-gray-600">Current:</div>
                        <img src="{{ $product->image_path }}" alt="Product image" class="mt-1 h-24 w-auto rounded border" />
                    @endif
                </div>
                <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                    <a href="{{ route('inventory.products.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
