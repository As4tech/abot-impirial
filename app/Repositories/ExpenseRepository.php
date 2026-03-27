<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ExpenseRepository
{
    public function queryForFilters(array $filters): Builder
    {
        return Expense::query()
            ->with(['category','creator'])
            ->when(!empty($filters['category_id']), fn($q)=>$q->where('category_id', $filters['category_id']))
            ->when(!empty($filters['payment_method']), fn($q)=>$q->where('payment_method', $filters['payment_method']))
            ->when(!empty($filters['date_from']), fn($q)=>$q->whereDate('expense_date','>=',$filters['date_from']))
            ->when(!empty($filters['date_to']), fn($q)=>$q->whereDate('expense_date','<=',$filters['date_to']))
            ->orderByDesc('expense_date')
            ->orderByDesc('id');
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->queryForFilters($filters)->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function totals(array $filters): array
    {
        $base = $this->queryForFilters($filters);
        $today = (clone $base)->whereDate('expense_date', now()->toDateString())->sum('amount');
        $week = (clone $base)->whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
        $month = (clone $base)->whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        return [
            'today' => (float) $today,
            'week' => (float) $week,
            'month' => (float) $month,
        ];
    }

    public function categoryBreakdown(array $filters): array
    {
        return $this->queryForFilters($filters)
            ->reorder()
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total','category_id')
            ->map(fn($v)=>(float)$v)
            ->toArray();
    }
}
