<?php

namespace App\Services;

use App\Events\LowStockDetected;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function recordMovement(Product $product, string $type, float $quantity, ?string $reference = null): StockMovement
    {
        $movement = $product->movements()->create([
            'type' => $type,
            'quantity' => $quantity,
            'reference' => $reference,
        ]);

        if ($type === 'in' || $type === 'adjustment') {
            $product->increment('stock_quantity', $quantity);
        } elseif ($type === 'out') {
            $product->decrement('stock_quantity', $quantity);
        }

        $this->checkLowStock($product->refresh());

        return $movement;
    }

    public function deductForOrder(int $orderId): void
    {
        $order = Order::with(['items' => function ($q) {
            $q->whereNotNull('product_id');
        }, 'items.product'])->findOrFail($orderId);

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if (! $product) {
                    continue;
                }
                $this->recordMovement($product, 'out', (float) $item->quantity, 'POS Order #'.$order->id);
            }
        });
    }

    public function checkLowStock(Product $product): void
    {
        $threshold = (float) (config('inventory.low_stock_threshold', env('INVENTORY_LOW_STOCK_THRESHOLD', 5)));
        if ($product->stock_quantity <= $threshold) {
            event(new LowStockDetected($product->id, $product->stock_quantity));
        }
    }
}
