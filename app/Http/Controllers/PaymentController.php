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

        return redirect()
            ->route('pos.receipt', $order)
            ->with('status', 'Payment recorded');
    }

    public function receipt(Order $order): View
    {
        $order->load(['items.product', 'items.menuItem', 'payments', 'user']);

        $paid = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $balance = max(0, (float) $order->total_amount - $paid);

        return view('pos.receipt', compact('order', 'paid', 'balance'));
    }

    public function receiptThermal(Order $order): View
    {
        $order->load(['items.product', 'items.menuItem', 'payments', 'user']);

        $paid = (float) $order->payments()->where('status', 'paid')->sum('amount');
        $balance = max(0, (float) $order->total_amount - $paid);

        return view('pos.receipt-thermal', compact('order', 'paid', 'balance'));
    }
}
