<?php

namespace App\Http\Controllers;

use App\Models\KitchenIngredient;
use App\Models\Supplier;
use App\Http\Requests\KitchenStockRequest;
use App\Http\Requests\KitchenStockAdjustmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KitchenStockController extends Controller
{
    public function index(Request $request): View
    {
        $query = KitchenIngredient::with('supplier')->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $ingredients = $query->paginate(20);
        $lowStockCount = KitchenIngredient::whereRaw('current_stock <= min_stock_level')->count();

        return view('kitchen-stock.index', compact('ingredients', 'lowStockCount'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('kitchen-stock.create', compact('suppliers'));
    }

    public function store(KitchenStockRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['active'] = $validated['active'] ?? true;

        $ingredient = KitchenIngredient::create($validated);

        // Create initial stock movement if current stock > 0
        if (isset($validated['current_stock']) && $validated['current_stock'] > 0) {
            $ingredient->addStock(
                $validated['current_stock'],
                $validated['cost_per_unit'],
                'Initial stock',
                $request->user()->id
            );
        }

        return redirect()
            ->route('kitchen-stock.index')
            ->with('status', 'Ingredient added successfully.');
    }

    public function show(KitchenIngredient $kitchenStock): View
    {
        $kitchenStock->load('supplier', 'stockMovements.user', 'recipes.menuItem');
        
        $recentMovements = $kitchenStock->stockMovements()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('kitchen-stock.show', compact('kitchenStock', 'recentMovements'));
    }

    public function edit(KitchenIngredient $kitchenStock): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('kitchen-stock.edit', compact('kitchenStock', 'suppliers'));
    }

    public function update(Request $request, KitchenIngredient $kitchenStock): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:kitchen_ingredients,name,' . $kitchenStock->id],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:20'],
            'min_stock_level' => ['required', 'numeric', 'min:0'],
            'cost_per_unit' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'active' => ['boolean'],
        ]);

        $kitchenStock->update($validated);

        return redirect()
            ->route('kitchen-stock.index')
            ->with('status', 'Ingredient updated successfully.');
    }

    public function destroy(KitchenIngredient $kitchenStock): RedirectResponse
    {
        // Check if ingredient is used in recipes
        if ($kitchenStock->recipes()->exists()) {
            return back()
                ->with('error', 'Cannot delete ingredient that is used in recipes.');
        }

        $kitchenStock->delete();

        return redirect()
            ->route('kitchen-stock.index')
            ->with('status', 'Kitchen ingredient deleted successfully.');
    }

    public function adjustStock(KitchenStockAdjustmentRequest $request, KitchenIngredient $kitchenStock): RedirectResponse
    {
        $validated = $request->validated();

        $quantity = $validated['quantity'];
        $unitCost = $validated['unit_cost'] ?? null;
        $notes = $validated['notes'] ?? null;

        switch ($validated['adjustment_type']) {
            case 'purchase':
                $kitchenStock->addStock($quantity, $unitCost, $notes, $request->user()->id);
                break;
            case 'waste':
                $kitchenStock->deductStock($quantity, 'waste', null, $notes, $request->user()->id);
                break;
            case 'adjustment':
                $kitchenStock->deductStock($quantity, 'adjustment', null, $notes, $request->user()->id);
                break;
        }

        return redirect()
            ->route('kitchen-stock.show', $kitchenStock)
            ->with('status', 'Stock adjusted successfully.');
    }

    public function lowStock(): View
    {
        $ingredients = KitchenIngredient::with('supplier')
            ->whereRaw('current_stock <= min_stock_level')
            ->orderBy('name')
            ->paginate(20);

        return view('kitchen-stock.low-stock', compact('ingredients'));
    }
}
