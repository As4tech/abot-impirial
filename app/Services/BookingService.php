<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BookingService
{
    public function checkIn(int $roomId, string $guestName, ?string $guestPhone = null): Booking
    {
        $room = Room::findOrFail($roomId);
        if ($room->status !== 'Available') {
            throw new InvalidArgumentException('Room is not available');
        }

        return DB::transaction(function () use ($room, $guestName, $guestPhone) {
            $guest = Guest::firstOrCreate(
                ['name' => $guestName, 'phone' => $guestPhone]
            );

            $booking = Booking::create([
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'check_in' => Carbon::now(),
                'status' => 'active',
            ]);

            $room->update(['status' => 'Occupied']);

            return $booking;
        });
    }

    public function checkOut(Booking $booking): Booking
    {
        if ($booking->status !== 'active') {
            throw new InvalidArgumentException('Booking is not active');
        }

        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'completed',
                'check_out' => Carbon::now(),
            ]);

            // Set room to Cleaning upon checkout
            $booking->room->update(['status' => 'Cleaning']);

            return $booking;
        });
    }
}
