<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $productId = $request->integer('product_id');
        $type = $request->string('type')->toString();
        $movements = StockMovement::with('product')
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($type, fn($q) => $q->where('type', $type))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        $products = Product::orderBy('name')->get();
        return view('inventory.movements.index', compact('movements', 'products', 'productId', 'type'));
    }
}
