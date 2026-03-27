<?php

namespace App\Repositories;

use App\Models\ExpenseCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseCategoryRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ExpenseCategory::query()->orderBy('name')->paginate($perPage);
    }

    public function all()
    {
        return ExpenseCategory::query()->orderBy('name')->get();
    }

    public function create(array $data): ExpenseCategory
    {
        return ExpenseCategory::create($data);
    }

    public function update(ExpenseCategory $category, array $data): ExpenseCategory
    {
        $category->update($data);
        return $category;
    }

    public function delete(ExpenseCategory $category): void
    {
        if ($category->expenses()->exists()) {
            throw new \RuntimeException('Cannot delete category with linked expenses.');
        }
        $category->delete();
    }
}
