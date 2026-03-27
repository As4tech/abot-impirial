<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'unit',
        'stock_quantity',
        'cost_price',
        'selling_price',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'decimal:3',
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
