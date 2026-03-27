<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categories = MenuCategory::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
        return view('menu_categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('menu_categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        MenuCategory::create($validated);
        return redirect()->route('menu-categories.index')->with('status', 'Category created');
    }

    public function edit(MenuCategory $menu_category): View
    {
        return view('menu_categories.edit', ['category' => $menu_category]);
    }

    public function update(Request $request, MenuCategory $menu_category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $menu_category->update($validated);
        return redirect()->route('menu-categories.index')->with('status', 'Category updated');
    }

    public function destroy(MenuCategory $menu_category): RedirectResponse
    {
        $menu_category->delete();
        return back()->with('status', 'Category deleted');
    }
}
