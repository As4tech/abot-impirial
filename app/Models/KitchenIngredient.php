<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class KitchenIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'current_stock',
        'min_stock_level',
        'cost_per_unit',
        'supplier_id',
        'active',
    ];

    protected $casts = [
        'current_stock' => 'decimal:4',
        'min_stock_level' => 'decimal:4',
        'cost_per_unit' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(KitchenStockMovement::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock_level;
    }

    public function addStock(float $quantity, ?float $unitCost = null, ?string $notes = null, ?int $userId = null): KitchenStockMovement
    {
        return DB::transaction(function () use ($quantity, $unitCost, $notes, $userId) {
            // Lock the row for update to prevent race conditions
            $freshIngredient = self::lockForUpdate()->find($this->id);
            
            // Create stock movement first
            $movement = $this->stockMovements()->create([
                'type' => 'purchase',
                'quantity' => $quantity,
                'unit_cost' => $unitCost ?? $this->cost_per_unit,
                'reference_type' => self::class,
                'reference_id' => $this->id,
                'notes' => $notes,
                'user_id' => $userId,
            ]);

            // Update stock atomically
            $freshIngredient->increment('current_stock', $quantity);
            
            return $movement;
        });
    }

    public function deductStock(float $quantity, string $type = 'usage', $reference = null, ?string $notes = null, ?int $userId = null): KitchenStockMovement
    {
        return DB::transaction(function () use ($quantity, $type, $reference, $notes, $userId) {
            // Lock the row for update to prevent race conditions
            $freshIngredient = self::lockForUpdate()->find($this->id);
            
            // Check if sufficient stock is available
            if ($freshIngredient->current_stock < $quantity) {
                throw new InvalidArgumentException(
                    "Insufficient stock for {$freshIngredient->name}. Available: {$freshIngredient->current_stock}, Required: {$quantity}"
                );
            }
            
            // Create stock movement first
            $movement = $this->stockMovements()->create([
                'type' => $type,
                'quantity' => -$quantity,
                'unit_cost' => $this->cost_per_unit,
                'reference_type' => $reference ? get_class($reference) : self::class,
                'reference_id' => $reference?->id ?? $this->id,
                'notes' => $notes,
                'user_id' => $userId,
            ]);

            // Update stock atomically
            $freshIngredient->decrement('current_stock', $quantity);
            
            return $movement;
        });
    }

    /**
     * Check if sufficient stock is available (with optional locking)
     */
    public function hasSufficientStock(float $quantity, bool $lockForUpdate = false): bool
    {
        $query = $lockForUpdate ? self::lockForUpdate() : self::query();
        $ingredient = $query->find($this->id);
        
        return $ingredient && $ingredient->current_stock >= $quantity;
    }
}
