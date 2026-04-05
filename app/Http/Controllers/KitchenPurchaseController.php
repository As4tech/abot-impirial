<?php

namespace App\Http\Controllers;

use App\Models\KitchenIngredient;
use App\Models\KitchenStockMovement;
use App\Models\Supplier;
use App\Http\Requests\KitchenPurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KitchenPurchaseController extends Controller
{
    public function index(Request $request): View
    {
        $query = KitchenStockMovement::with('ingredient', 'user')
            ->where('type', 'purchase')
            ->orderBy('created_at', 'desc');

        if ($request->filled('ingredient_id')) {
            $query->where('kitchen_ingredient_id', $request->ingredient_id);
        }

        if ($request->filled('supplier_id')) {
            $query->whereHas('ingredient', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $purchases = $query->paginate(20);
        $ingredients = KitchenIngredient::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('kitchen-purchases.index', compact('purchases', 'ingredients', 'suppliers'));
    }

    public function create(): View
    {
        $ingredients = KitchenIngredient::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        // Group ingredients by supplier for easier selection
        $ingredientsBySupplier = $ingredients->groupBy('supplier_id');

        return view('kitchen-purchases.create', compact('ingredients', 'suppliers', 'ingredientsBySupplier'));
    }

    public function store(KitchenPurchaseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $totalCost = 0;
        $purchasedItems = [];

        foreach ($validated['items'] as $item) {
            $ingredient = KitchenIngredient::findOrFail($item['ingredient_id']);
            
            // Add stock and record movement
            $invoiceNumber = $validated['invoice_number'] ?? 'N/A';
            $notes = $item['notes'] ?? "Purchase #{$invoiceNumber}";
            $movement = $ingredient->addStock(
                $item['quantity'],
                $item['unit_cost'],
                $notes,
                $request->user()->id
            );

            $totalCost += $item['quantity'] * $item['unit_cost'];
            $purchasedItems[] = [
                'ingredient' => $ingredient->name,
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total' => $item['quantity'] * $item['unit_cost'],
            ];
        }

        return redirect()
            ->route('kitchen-purchases.index')
            ->with('status', "Purchase recorded successfully. Total cost: " . number_format($totalCost, 2));
    }

    public function show(KitchenStockMovement $purchase): View
    {
        if ($purchase->type !== 'purchase') {
            abort(404);
        }

        $purchase->load('ingredient', 'user');

        return view('kitchen-purchases.show', compact('purchase'));
    }

    public function lowStock(): View
    {
        $ingredients = KitchenIngredient::with('supplier')
            ->where('active', true)
            ->whereRaw('current_stock <= min_stock_level')
            ->orderBy('name')
            ->get();

        return view('kitchen-purchases.low-stock', compact('ingredients'));
    }
}
