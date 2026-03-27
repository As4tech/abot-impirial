<?php

namespace App\Services;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(private ExpenseRepository $expenses)
    {
    }

    public function create(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            return $this->expenses->create($data);
        });
    }

    public function listWithFilters(array $filters, int $perPage = 15)
    {
        return $this->expenses->paginate($filters, $perPage);
    }

    public function summary(array $filters): array
    {
        return $this->expenses->totals($filters);
    }

    public function breakdownByCategory(array $filters): array
    {
        return $this->expenses->categoryBreakdown($filters);
    }
}
