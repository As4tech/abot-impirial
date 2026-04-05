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
        $date = $request->string('date')->toString();
        
        $query = Order::with(['items', 'items.product', 'items.menuItem'])
            ->whereIn('status', ['Pending', 'Preparing', 'Served'])
            ->orderByDesc('id');

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('kitchen.index', compact('orders', 'status', 'date'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Preparing,Served'],
        ]);
        $order->update(['status' => $validated['status']]);
        return back()->with('status', 'Order status updated');
    }

    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Preparing,Served'],
        ]);

        $count = Order::where('status', 'Pending')->update(['status' => $validated['status']]);

        return back()->with('status', "{$count} pending orders marked as {$validated['status']}");
    }
}
