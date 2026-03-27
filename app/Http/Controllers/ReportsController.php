<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\StockMovement;
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

        return view('reports.inventory-movement', [
            'series' => $series,
            'days' => $days,
        ]);
    }
}
