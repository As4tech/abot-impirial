<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'method' => ['required', 'in:cash,mobile_money'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['nullable', 'in:paid,pending,failed'],
        ]);

        $order = Order::with('payments')->findOrFail($validated['order_id']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $validated['method'],
            'amount' => $validated['amount'],
            'status' => $validated['status'] ?? 'paid',
        ]);

        // Check if order is now fully paid and update status
        $paidAmount = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $isFullyPaid = $paidAmount >= (float) $order->total_amount;

        if ($isFullyPaid && $order->status !== 'Completed') {
            $order->update(['status' => 'Completed']);
            
            // If this is a room order, free the room
            if ($order->room_id && $order->order_type === 'room') {
                $order->room()->update(['status' => 'Available']);
            }
        }

        $statusMessage = $validated['status'] === 'paid' 
            ? "Payment of {$validated['amount']} recorded successfully via {$validated['method']}!"
            : "Payment of {$validated['amount']} recorded as {$validated['status']} via {$validated['method']}.";

        return redirect()
            ->route('pos.payment.confirmation', $order)
            ->with('status', $statusMessage);
    }

    public function receipt(Order $order): View
    {
        $order->load(['items.product', 'items.menuItem', 'payments', 'user']);

        $paid = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $balance = max(0, (float) $order->total_amount - $paid);

        return view('pos.receipt', compact('order', 'paid', 'balance'));
    }

    public function paymentConfirmation(Order $order): View
    {
        $currency = function_exists('setting') ? (setting('pos.currency') ?: 'PHP') : 'PHP';
        $order->load(['items.product', 'items.menuItem', 'payments', 'user']);

        return view('pos.payment-confirmation', compact('order', 'currency'));
    }

    public function receiptThermal(Order $order): View
    {
        $order->load(['items.product', 'items.menuItem', 'payments', 'user']);

        $paid = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $balance = max(0, (float) $order->total_amount - $paid);

        return view('pos.receipt-thermal', compact('order', 'paid', 'balance'));
    }
}
