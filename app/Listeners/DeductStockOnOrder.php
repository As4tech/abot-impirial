<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\InventoryService;

class DeductStockOnOrder
{
    public function __construct(private readonly InventoryService $inventory)
    {
    }

    public function handle(OrderPlaced $event): void
    {
        // Deduct only product-based items; menu items are not tracked as inventory here
        $this->inventory->deductForOrder($event->orderId);
    }
}
