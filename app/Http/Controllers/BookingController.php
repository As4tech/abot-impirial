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

        // Use the original price that was charged at check-in
        // This preserves custom pricing that may have been set during check-in
        $charge = round((float) ($booking->initial_charge ?? 0), 2);

        // For consistency, also update computed_charge to match what was actually charged
        // This ensures the booking record reflects the actual pricing used
        $finalCharge = $charge;

        // No additional charge needed since we're using the original price
        $delta = 0;

        // Update booking and order
        $booking->update([
            'computed_charge' => $finalCharge,
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

            $order->update([
                'total_amount' => round(($order->total_amount ?? 0) + $delta, 2),
            ]);
        }

        // Check if order has been fully paid
        $paidAmount = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $isFullyPaid = $paidAmount >= (float) $order->total_amount;

        if ($isFullyPaid) {
            // Order is fully paid, complete it
            $order->update(['status' => 'Completed']);
            
            // Free room
            Room::where('id', $room->id)->update(['status' => 'Available']);

            return redirect()->route('pos.payment.confirmation', $order)
                ->with('status', 'Room checked out successfully! Order fully paid.');
        } else {
            // Order has pending or insufficient payments, redirect to payment confirmation
            return redirect()->route('pos.payment.confirmation', $order)
                ->with('status', 'Room checked out! Please record payment to complete the order.');
        }
    }
}
