<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function __construct(private readonly InventoryService $inventory)
    {
    }

    public function index(Request $request): View
    {
        $supplierId = $request->integer('supplier_id');
        $purchases = Purchase::with('supplier')
            ->when($supplierId, fn($q) => $q->where('supplier_id', $supplierId))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();
        return view('inventory.purchases.index', compact('purchases', 'suppliers', 'supplierId'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('inventory.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.001'],
            'items.*.cost_price' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase = DB::transaction(function () use ($validated) {
            $total = 0.0;
            foreach ($validated['items'] as $it) {
                $total += ((float)$it['quantity']) * ((float)$it['cost_price']);
            }

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'total_cost' => $total,
            ]);

            foreach ($validated['items'] as $it) {
                $item = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => (int) $it['product_id'],
                    'quantity' => (float) $it['quantity'],
                    'cost_price' => (float) $it['cost_price'],
                ]);

                // increase stock and record movement
                $product = Product::findOrFail($item->product_id);
                $this->inventory->recordMovement($product, 'in', (float) $item->quantity, 'Purchase #'.$purchase->id);
            }

            return $purchase;
        });

        return redirect()->route('inventory.purchases.show', $purchase)->with('status', 'Purchase recorded');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'items.product']);
        return view('inventory.purchases.show', compact('purchase'));
    }
}
