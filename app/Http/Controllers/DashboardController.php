<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $range = $request->string('range')->toString() ?: 'today';
        $fromInput = $request->date('from');
        $toInput = $request->date('to');

        // Resolve period
        $now = Carbon::now();
        $from = $now->copy()->startOfDay();
        $to = $now->copy()->endOfDay();
        $rangeLabel = 'Today';

        if ($fromInput && $toInput) {
            $from = Carbon::parse($fromInput)->startOfDay();
            $to = Carbon::parse($toInput)->endOfDay();
            $range = 'custom';
            $rangeLabel = $from->isSameDay($to)
                ? $from->toFormattedDateString()
                : $from->toFormattedDateString() . ' — ' . $to->toFormattedDateString();
        } else {
            switch ($range) {
                case 'week':
                    $from = $now->copy()->startOfWeek();
                    $to = $now->copy()->endOfWeek();
                    $rangeLabel = 'This Week';
                    break;
                case 'month':
                    $from = $now->copy()->startOfMonth();
                    $to = $now->copy()->endOfMonth();
                    $rangeLabel = 'This Month';
                    break;
                case 'today':
                default:
                    $from = $now->copy()->startOfDay();
                    $to = $now->copy()->endOfDay();
                    $rangeLabel = 'Today';
            }
        }

        // Sales & orders in period
        $salesTotal = (float) Order::whereBetween('created_at', [$from, $to])->sum('total_amount');
        $ordersCount = (int) Order::whereBetween('created_at', [$from, $to])->count();
        $avgOrder = $ordersCount > 0 ? $salesTotal / $ordersCount : 0.0;

        // Revenue breakdown
        $restaurantRevenue = (float) OrderItem::whereNotNull('menu_item_id')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COALESCE(SUM(quantity * price), 0) as total')
            ->value('total');

        $productsRevenue = (float) OrderItem::whereNotNull('product_id')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COALESCE(SUM(quantity * price), 0) as total')
            ->value('total');

        $roomServiceRevenue = (float) Order::where('order_type', 'room')
            ->whereBetween('created_at', [$from, $to])
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

        // Period expenses and profit
        $expensesTotal = (float) Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])->sum('amount');
        $profitTotal = $salesTotal - $expensesTotal;

        return view('dashboard', [
            'range' => $range,
            'rangeLabel' => $rangeLabel,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'todaysSales' => $salesTotal,
            'todaysOrders' => $ordersCount,
            'avgOrder' => $avgOrder,
            'breakdown' => $breakdown,
            'breakdownTotal' => $breakdownTotal,
            'lowStock' => $lowStock,
            'todaysExpenses' => $expensesTotal,
            'profitToday' => $profitTotal,
        ]);
    }
}
