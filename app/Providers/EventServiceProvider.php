<?php

namespace App\Providers;

use App\Events\LowStockDetected;
use App\Events\OrderPlaced;
use App\Listeners\DeductStockOnOrder;
use App\Listeners\LogLowStockAlert;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            DeductStockOnOrder::class,
        ],
        LowStockDetected::class => [
            LogLowStockAlert::class,
        ],
    ];
}
