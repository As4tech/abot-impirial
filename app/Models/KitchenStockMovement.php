<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitchenStockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'kitchen_ingredient_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:2',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(KitchenIngredient::class, 'kitchen_ingredient_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): BelongsTo
    {
        return $this->morphTo();
    }

    public function getQuantityFormattedAttribute(): string
    {
        $abs = abs($this->quantity);
        $prefix = $this->quantity < 0 ? '-' : '+';
        return $prefix . number_format($abs, 4) . ' ' . $this->ingredient->unit;
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'purchase' => 'Purchase',
            'usage' => 'Usage',
            'waste' => 'Waste',
            'adjustment' => 'Adjustment',
            default => ucfirst($this->type),
        };
    }
}
