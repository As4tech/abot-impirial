<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category_id');
        $products = Product::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();
        $threshold = (float) config('inventory.low_stock_threshold');
        return view('inventory.products.index', compact('products', 'categories', 'search', 'categoryId', 'threshold'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'unit' => ['nullable', 'string', 'max:50'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        // Keep legacy price field in sync if selling_price provided
        if (isset($validated['selling_price'])) {
            $validated['price'] = $validated['selling_price'];
        }
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('uploads/products', 'public');
            $validated['image_path'] = '/storage/' . $stored;
        }
        Product::create($validated);
        return redirect()->route('inventory.products.index')->with('status', 'Product created');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('inventory.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'unit' => ['nullable', 'string', 'max:50'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        if (isset($validated['selling_price'])) {
            $validated['price'] = $validated['selling_price'];
        }
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('uploads/products', 'public');
            $validated['image_path'] = '/storage/' . $stored;
        }
        $product->update($validated);
        return redirect()->route('inventory.products.index')->with('status', 'Product updated');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return back()->with('status', 'Product deleted');
    }
}
