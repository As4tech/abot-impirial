<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class LogLowStockAlert
{
    public function handle(LowStockDetected $event): void
    {
        $product = Product::find($event->productId);
        $name = $product?->name ?? ('#'.$event->productId);
        Log::warning('Low stock detected', [
            'product_id' => $event->productId,
            'product' => $name,
            'stock' => $event->stock,
        ]);
    }
}
