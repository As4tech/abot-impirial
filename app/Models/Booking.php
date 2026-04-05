<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'room_id', 'rate_type', 'hourly_rate', 'nightly_rate', 'initial_charge', 'computed_charge', 'check_in_at', 'check_out_at', 'status',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}