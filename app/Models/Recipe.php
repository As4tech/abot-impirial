<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'kitchen_ingredient_id',
        'quantity_required',
        'unit',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(KitchenIngredient::class, 'kitchen_ingredient_id');
    }

    /**
     * Deduct ingredient stock for a given quantity of menu items
     */
    public function deductStockForQuantity(int $quantity, $reference = null, ?int $userId = null): void
    {
        $totalQuantity = $this->quantity_required * $quantity;
        $this->ingredient->deductStock(
            $totalQuantity,
            'usage',
            $reference,
            "Used for {$quantity}x {$this->menuItem->name}",
            $userId
        );
    }
}
