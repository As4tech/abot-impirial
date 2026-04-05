<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'room_type_id', 'type', 'stay_type', 'price', 'long_price', 'short_price',
        'has_ac', 'has_fan', 'has_tv', 'has_fridge', 'bed_type',
        'status', 'image_path',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function getTypeAttribute(): ?string
    {
        return $this->roomType?->name;
    }
}
