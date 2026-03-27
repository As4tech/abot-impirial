<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = Order::with(['items', 'items.product', 'items.menuItem'])
            ->whereIn('status', ['Pending', 'Preparing', 'Served'])
            ->orderByDesc('id');

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('kitchen.index', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Preparing,Served'],
        ]);
        $order->update(['status' => $validated['status']]);
        return back()->with('status', 'Order status updated');
    }
}
