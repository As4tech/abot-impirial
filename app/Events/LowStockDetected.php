<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class LowStockDetected
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public int $productId, public float $stock)
    {
    }
}
