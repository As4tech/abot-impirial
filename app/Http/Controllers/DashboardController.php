<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        // Today's sales (sum of order totals today)
        $todaysSales = (float) Order::whereDate('created_at', $today)->sum('total_amount');
        $todaysOrders = (int) Order::whereDate('created_at', $today)->count();
        $avgOrder = $todaysOrders > 0 ? $todaysSales / $todaysOrders : 0.0;

        // Revenue breakdown
        $restaurantRevenue = (float) OrderItem::whereNotNull('menu_item_id')
            ->whereDate('created_at', $today)
            ->selectRaw('COALESCE(SUM(quantity * price), 0) as total')
            ->value('total');

        $productsRevenue = (float) OrderItem::whereNotNull('product_id')
            ->whereDate('created_at', $today)
            ->selectRaw('COALESCE(SUM(quantity * price), 0) as total')
            ->value('total');

        $roomServiceRevenue = (float) Order::where('order_type', 'room')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        // Low stock alerts (from settings with fallback)
        $threshold = (int) (function_exists('setting') ? setting('inventory.low_stock_threshold', config('inventory.threshold', 5)) : config('inventory.threshold', 5));
        $lowStock = Product::where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get(['id','name','stock_quantity','unit']);

        $breakdown = [
            'restaurant' => $restaurantRevenue,
            'room' => $roomServiceRevenue,
            'products' => $productsRevenue,
        ];
        $breakdownTotal = max(0.00001, array_sum($breakdown));

        // Today's expenses and profit
        $todaysExpenses = (float) Expense::whereDate('expense_date', $today)->sum('amount');
        $profitToday = $todaysSales - $todaysExpenses;

        return view('dashboard', [
            'todaysSales' => $todaysSales,
            'todaysOrders' => $todaysOrders,
            'avgOrder' => $avgOrder,
            'breakdown' => $breakdown,
            'breakdownTotal' => $breakdownTotal,
            'lowStock' => $lowStock,
            'todaysExpenses' => $todaysExpenses,
            'profitToday' => $profitToday,
        ]);
    }
}
