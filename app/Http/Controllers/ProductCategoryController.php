<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categories = ProductCategory::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();
        return view('inventory.categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('inventory.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        ProductCategory::create($validated);
        return redirect()->route('inventory.categories.index')->with('status', 'Category created');
    }

    public function edit(ProductCategory $product_category): View
    {
        return view('inventory.categories.edit', ['category' => $product_category]);
    }

    public function update(Request $request, ProductCategory $product_category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $product_category->update($validated);
        return redirect()->route('inventory.categories.index')->with('status', 'Category updated');
    }

    public function destroy(ProductCategory $product_category): RedirectResponse
    {
        $product_category->delete();
        return back()->with('status', 'Category deleted');
    }
}
