<?php

namespace App\Http\Controllers;

use App\Models\KitchenIngredient;
use App\Models\MenuItem;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Recipe::with('menuItem', 'ingredient')->orderBy('menu_item_id');

        if ($request->filled('menu_item_id')) {
            $query->where('menu_item_id', $request->menu_item_id);
        }

        if ($request->filled('ingredient_id')) {
            $query->where('kitchen_ingredient_id', $request->ingredient_id);
        }

        $recipes = $query->paginate(20);
        $menuItems = MenuItem::orderBy('name')->get();
        $ingredients = KitchenIngredient::where('active', true)->orderBy('name')->get();

        return view('recipes.index', compact('recipes', 'menuItems', 'ingredients'));
    }

    public function create(): View
    {
        $menuItems = MenuItem::orderBy('name')->get();
        $ingredients = KitchenIngredient::where('active', true)->orderBy('name')->get();

        return view('recipes.create', compact('menuItems', 'ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'kitchen_ingredient_id' => ['required', 'integer', 'exists:kitchen_ingredients,id'],
            'quantity_required' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['required', 'string'],
        ]);

        // Check if recipe already exists
        $exists = Recipe::where('menu_item_id', $validated['menu_item_id'])
            ->where('kitchen_ingredient_id', $validated['kitchen_ingredient_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This ingredient is already in the recipe for this menu item.');
        }

        Recipe::create($validated);

        return redirect()
            ->route('recipes.index')
            ->with('status', 'Recipe ingredient added successfully.');
    }

    public function show(Recipe $recipe): View
    {
        $recipe->load('menuItem', 'ingredient');

        return view('recipes.show', compact('recipe'));
    }

    public function edit(Recipe $recipe): View
    {
        $recipe->load('menuItem', 'ingredient');
        $menuItems = MenuItem::orderBy('name')->get();
        $ingredients = KitchenIngredient::where('active', true)->orderBy('name')->get();

        return view('recipes.edit', compact('recipe', 'menuItems', 'ingredients'));
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'kitchen_ingredient_id' => ['required', 'integer', 'exists:kitchen_ingredients,id'],
            'quantity_required' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['required', 'string'],
        ]);

        // Check if recipe already exists (excluding current)
        $exists = Recipe::where('menu_item_id', $validated['menu_item_id'])
            ->where('kitchen_ingredient_id', $validated['kitchen_ingredient_id'])
            ->where('id', '!=', $recipe->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This ingredient is already in the recipe for this menu item.');
        }

        $recipe->update($validated);

        return redirect()
            ->route('recipes.index')
            ->with('status', 'Recipe updated successfully.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with('status', 'Recipe ingredient removed successfully.');
    }

    public function forMenuItem(MenuItem $menuItem): View
    {
        $recipes = $menuItem->recipes()
            ->with('ingredient')
            ->orderBy('ingredient.name')
            ->get();

        $availableIngredients = KitchenIngredient::where('active', true)
            ->whereNotIn('id', $recipes->pluck('kitchen_ingredient_id'))
            ->orderBy('name')
            ->get();

        return view('recipes.for-menu-item', compact('menuItem', 'recipes', 'availableIngredients'));
    }

    public function addIngredient(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $request->validate([
            'kitchen_ingredient_id' => ['required', 'integer', 'exists:kitchen_ingredients,id'],
            'quantity_required' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['required', 'string'],
        ]);

        // Check if already exists
        $exists = Recipe::where('menu_item_id', $menuItem->id)
            ->where('kitchen_ingredient_id', $validated['kitchen_ingredient_id'])
            ->exists();

        if ($exists) {
            return back()
                ->with('error', 'This ingredient is already in the recipe.');
        }

        Recipe::create([
            'menu_item_id' => $menuItem->id,
            ...$validated,
        ]);

        return redirect()
            ->route('recipes.for-menu-item', $menuItem)
            ->with('status', 'Ingredient added to recipe successfully.');
    }
}
