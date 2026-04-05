<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\MenuItem;
use App\Models\Register;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 30);
        $from = Carbon::today()->subDays($days - 1)->startOfDay();

        $totalSales = (float) Order::where('created_at', '>=', $from)->sum('total_amount');
        $totalExpenses = (float) Expense::where('expense_date', '>=', $from->toDateString())->sum('amount');
        $profit = $totalSales - $totalExpenses;

        return view('reports', compact('days','totalSales','totalExpenses','profit'));
    }

    public function productProfit(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = $request->date('from')?->startOfDay() ?? Carbon::today()->subDays($days - 1)->startOfDay();
        $to = $request->date('to')?->endOfDay() ?? Carbon::today()->endOfDay();

        // Daily series: revenue, cost, profit
        $rows = OrderItem::query()
            ->select(
                DB::raw('DATE(order_items.created_at) as d'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity * COALESCE(products.cost_price, 0)) as cost')
            )
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereNotNull('order_items.product_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(order_items.created_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        $periodDays = $from->diffInDays($to) + 1;
        for ($i = 0; $i < $periodDays; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $rev = (float) ($found->revenue ?? 0);
            $cost = (float) ($found->cost ?? 0);
            $series[] = [
                'date' => $date,
                'revenue' => $rev,
                'cost' => $cost,
                'profit' => $rev - $cost,
            ];
        }

        // Per-product totals for the period
        $details = OrderItem::query()
            ->select(
                'order_items.product_id',
                DB::raw('COALESCE(products.name, CONCAT("Product #", order_items.product_id)) as name'),
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('SUM(order_items.quantity * COALESCE(products.cost_price, 0)) as total_cost')
            )
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereNotNull('order_items.product_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc(DB::raw('SUM(order_items.quantity * order_items.price) - SUM(order_items.quantity * COALESCE(products.cost_price, 0))'))
            ->get()
            ->map(function ($row) {
                $row->total_profit = (float) $row->total_revenue - (float) $row->total_cost;
                return $row;
            });

        if ($request->query('export') === 'csv') {
            $filename = 'product-profit-'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename='.$filename,
            ];
            $callback = function () use ($series, $details) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Date', 'Revenue', 'Cost', 'Profit']);
                foreach ($series as $row) {
                    fputcsv($out, [$row['date'], $row['revenue'], $row['cost'], $row['profit']]);
                }
                fputcsv($out, []);
                fputcsv($out, ['Product', 'Qty', 'Revenue', 'Cost', 'Profit']);
                foreach ($details as $d) {
                    fputcsv($out, [$d->name, (int) $d->total_qty, (float) $d->total_revenue, (float) $d->total_cost, (float) $d->total_profit]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('reports.products-profit', [
            'series' => $series,
            'days' => $days,
            'from' => $from,
            'to' => $to,
            'details' => $details,
        ]);
    }

    public function menuItemsSales(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = $request->date('from')?->startOfDay() ?? Carbon::today()->subDays($days - 1)->startOfDay();
        $to = $request->date('to')?->endOfDay() ?? Carbon::today()->endOfDay();

        // Daily series: total menu items sold and revenue per day
        $rows = OrderItem::query()
            ->select(
                DB::raw('DATE(order_items.created_at) as d'),
                DB::raw('SUM(quantity) as items'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->whereNotNull('menu_item_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(order_items.created_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        $periodDays = $from->diffInDays($to) + 1;
        for ($i = 0; $i < $periodDays; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $series[] = [
                'date' => $date,
                'items' => (int) ($found->items ?? 0),
                'revenue' => (float) ($found->revenue ?? 0),
            ];
        }

        // Per-item totals for the period
        $details = OrderItem::query()
            ->select(
                'order_items.menu_item_id',
                DB::raw('COALESCE(menu_items.name, CONCAT("Item #", order_items.menu_item_id)) as name'),
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->whereNotNull('order_items.menu_item_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy('order_items.menu_item_id', 'menu_items.name')
            ->orderByDesc(DB::raw('SUM(order_items.quantity * order_items.price)'))
            ->get();

        if ($request->query('export') === 'csv') {
            $filename = 'menu-items-sales-'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename='.$filename,
            ];
            $callback = function () use ($series, $details) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Date', 'Items', 'Revenue']);
                foreach ($series as $row) {
                    fputcsv($out, [$row['date'], $row['items'], $row['revenue']]);
                }
                fputcsv($out, []);
                fputcsv($out, ['Menu Item', 'Total Qty', 'Total Revenue']);
                foreach ($details as $d) {
                    fputcsv($out, [$d->name, (int) $d->total_qty, (float) $d->total_revenue]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('reports.menu-items', [
            'series' => $series,
            'days' => $days,
            'from' => $from,
            'to' => $to,
            'details' => $details,
        ]);
    }

    public function productSales(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = $request->date('from')?->startOfDay() ?? Carbon::today()->subDays($days - 1)->startOfDay();
        $to = $request->date('to')?->endOfDay() ?? Carbon::today()->endOfDay();

        // Daily series: total products sold and revenue per day
        $rows = OrderItem::query()
            ->select(
                DB::raw('DATE(order_items.created_at) as d'),
                DB::raw('SUM(quantity) as items'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->whereNotNull('product_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(order_items.created_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        $periodDays = $from->diffInDays($to) + 1;
        for ($i = 0; $i < $periodDays; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $series[] = [
                'date' => $date,
                'items' => (int) ($found->items ?? 0),
                'revenue' => (float) ($found->revenue ?? 0),
            ];
        }

        // Per-product totals for the period
        $details = OrderItem::query()
            ->select(
                'order_items.product_id',
                DB::raw('COALESCE(products.name, CONCAT("Product #", order_items.product_id)) as name'),
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereNotNull('order_items.product_id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc(DB::raw('SUM(order_items.quantity * order_items.price)'))
            ->get();

        if ($request->query('export') === 'csv') {
            $filename = 'product-sales-'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename='.$filename,
            ];
            $callback = function () use ($series, $details) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Date', 'Items', 'Revenue']);
                foreach ($series as $row) {
                    fputcsv($out, [$row['date'], $row['items'], $row['revenue']]);
                }
                fputcsv($out, []);
                fputcsv($out, ['Product', 'Total Qty', 'Total Revenue']);
                foreach ($details as $d) {
                    fputcsv($out, [$d->name, (int) $d->total_qty, (float) $d->total_revenue]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('reports.products', [
            'series' => $series,
            'days' => $days,
            'from' => $from,
            'to' => $to,
            'details' => $details,
        ]);
    }

    public function registers(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = $request->date('from')?->startOfDay() ?? Carbon::today()->subDays($days - 1)->startOfDay();
        $to = $request->date('to')?->endOfDay() ?? Carbon::today()->endOfDay();

        $sessions = Register::with('user')
            ->whereBetween('opened_at', [$from, $to])
            ->orderByDesc('opened_at')
            ->get(['id','user_id','opened_at','closed_at','opening_amount','closing_amount','status','notes']);

        $sessions->transform(function ($s) {
            $start = $s->opened_at ?? Carbon::minValue();
            $end = $s->closed_at ?? Carbon::now();
            $cash = DB::table('payments')
                ->join('orders', 'orders.id', '=', 'payments.order_id')
                ->where('orders.user_id', $s->user_id)
                ->where('payments.method', 'cash')
                ->where('payments.status', 'paid')
                ->whereBetween('payments.created_at', [$start, $end])
                ->sum('payments.amount');
            $momo = DB::table('payments')
                ->join('orders', 'orders.id', '=', 'payments.order_id')
                ->where('orders.user_id', $s->user_id)
                ->where('payments.method', 'mobile_money')
                ->where('payments.status', 'paid')
                ->whereBetween('payments.created_at', [$start, $end])
                ->sum('payments.amount');

            $s->cash_collected = (float) $cash;
            $s->mobile_money_collected = (float) $momo;
            $s->expected_cash_drawer = (float) ($s->opening_amount + $cash);
            $s->variance = $s->closing_amount !== null ? (float) ($s->closing_amount - $s->expected_cash_drawer) : null;
            return $s;
        });

        $summary = [
            'opened' => $sessions->count(),
            'closed' => $sessions->whereNotNull('closed_at')->count(),
            'cash_collected' => (float) $sessions->sum('cash_collected'),
            'mobile_money_collected' => (float) $sessions->sum('mobile_money_collected'),
        ];

        if ($request->query('export') === 'csv') {
            $filename = 'register-sessions-'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename='.$filename,
            ];
            $callback = function () use ($sessions) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['User', 'Opened At', 'Opening Amount', 'Closed At', 'Closing Amount', 'Cash Collected', 'Mobile Money', 'Expected Cash Drawer', 'Variance', 'Status', 'Notes']);
                foreach ($sessions as $s) {
                    fputcsv($out, [
                        optional($s->user)->name ?? ('User #'.$s->user_id),
                        optional($s->opened_at)->format('Y-m-d H:i'),
                        $s->opening_amount,
                        optional($s->closed_at)->format('Y-m-d H:i'),
                        $s->closing_amount,
                        $s->cash_collected,
                        $s->mobile_money_collected,
                        $s->expected_cash_drawer,
                        $s->variance,
                        $s->status,
                        $s->notes,
                    ]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('reports.registers', [
            'days' => $days,
            'from' => $from,
            'to' => $to,
            'sessions' => $sessions,
            'summary' => $summary,
        ]);
    }
    public function dailySales(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = Carbon::today()->subDays($days - 1);

        $rows = Order::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', $from->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $series[] = [
                'date' => $date,
                'total' => (float) ($found->total ?? 0),
            ];
        }

        return view('reports.daily-sales', [
            'series' => $series,
            'days' => $days,
        ]);
    }

    public function inventoryMovement(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = Carbon::today()->subDays($days - 1);

        // Aggregate data for chart
        $rows = StockMovement::select(
                DB::raw('DATE(created_at) as d'),
                DB::raw("SUM(CASE WHEN type='in' THEN quantity WHEN type='out' THEN -quantity ELSE quantity END) as net_qty")
            )
            ->where('created_at', '>=', $from->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $series[] = [
                'date' => $date,
                'net' => (float) ($found->net_qty ?? 0),
            ];
        }

        // Detailed movements for table
        $movements = StockMovement::with(['product'])
            ->where('created_at', '>=', $from->startOfDay())
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('reports.inventory-movement', [
            'series' => $series,
            'days' => $days,
            'movements' => $movements,
        ]);
    }

    public function bookings(Request $request): View
    {
        $days = (int) ($request->integer('days') ?: 14);
        $from = Carbon::today()->subDays($days - 1);
        $status = $request->string('status')->toString(); // '', 'active', 'completed', 'cancelled'
        $roomId = $request->integer('room_id');

        $query = Booking::query()
            ->select(
                DB::raw('DATE(check_in_at) as d'),
                DB::raw('COUNT(*) as checkins'),
                DB::raw('SUM(COALESCE(computed_charge, initial_charge, 0)) as revenue')
            )
            ->where('check_in_at', '>=', $from->startOfDay());

        if (!empty($status)) {
            $query->where('status', $status);
        }
        if (!empty($roomId)) {
            $query->where('room_id', $roomId);
        }

        $rows = $query
            ->groupBy(DB::raw('DATE(check_in_at)'))
            ->orderBy('d')
            ->get();

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i)->toDateString();
            $found = $rows->firstWhere('d', $date);
            $series[] = [
                'date' => $date,
                'checkins' => (int) ($found->checkins ?? 0),
                'revenue' => (float) ($found->revenue ?? 0),
            ];
        }

        $rooms = Room::orderBy('room_number')->get(['id','room_number']);
        
        // Detailed rows for the selected period
        $detailsQuery = Booking::with('room')
            ->where('check_in_at', '>=', $from->startOfDay())
            ->orderByDesc('check_in_at');
        if (!empty($status)) {
            $detailsQuery->where('status', $status);
        }
        if (!empty($roomId)) {
            $detailsQuery->where('room_id', $roomId);
        }
        $details = $detailsQuery->get(['id','order_id','room_id','initial_charge','computed_charge','check_in_at','check_out_at','status']);

        return view('reports.bookings', [
            'series' => $series,
            'days' => $days,
            'status' => $status,
            'roomId' => $roomId,
            'rooms' => $rooms,
            'details' => $details,
        ]);
    }
}
