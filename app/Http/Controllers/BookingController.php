<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['room','order'])
            ->where('status','active')
            ->latest('check_in_at')
            ->get();
        return view('pos.stays', compact('bookings'));
    }

    public function checkout(Booking $booking, Request $request): RedirectResponse
    {
        abort_unless($booking->status === 'active', 404);
        $order = $booking->order;
        $room = $booking->room;

        // Compute final charge
        $charge = 0.0;
        if ($booking->rate_type === 'short') {
            $charge = round((float) ($room->short_price ?? $room->price ?? 0), 2);
        } else {
            $charge = round((float) ($room->long_price ?? $room->price ?? 0), 2);
        }

        // Determine delta (final minus any initial charge already billed at check-in)
        $initial = (float) ($booking->initial_charge ?? 0);
        $delta = round(max(0, $charge - $initial), 2);

        // Update booking and order
        $booking->update([
            'computed_charge' => $charge,
            'check_out_at' => now(),
            'status' => 'completed',
        ]);

        // Bill only the difference if any
        if ($delta > 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null,
                'menu_item_id' => null,
                'quantity' => 1,
                'price' => $delta,
            ]);
        }

        $order->update([
            'total_amount' => round(($order->total_amount ?? 0) + $delta, 2),
            'status' => 'Completed',
        ]);

        // Free room
        Room::where('id', $room->id)->update(['status' => 'Available']);

        return back()->with('status', 'Checked out successfully');
    }
}
