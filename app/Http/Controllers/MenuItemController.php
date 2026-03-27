<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category_id');
        $items = MenuItem::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();
        $categories = MenuCategory::orderBy('name')->get();
        return view('menu_items.index', compact('items', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = MenuCategory::orderBy('name')->get();
        return view('menu_items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        MenuItem::create($validated);
        return redirect()->route('menu-items.index')->with('status', 'Menu item created');
    }

    public function edit(MenuItem $menu_item): View
    {
        $categories = MenuCategory::orderBy('name')->get();
        return view('menu_items.edit', ['item' => $menu_item, 'categories' => $categories]);
    }

    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        $menu_item->update($validated);
        return redirect()->route('menu-items.index')->with('status', 'Menu item updated');
    }

    public function destroy(MenuItem $menu_item): RedirectResponse
    {
        $menu_item->delete();
        return back()->with('status', 'Menu item deleted');
    }
}
