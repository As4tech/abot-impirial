<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Repositories\ExpenseCategoryRepository;
use App\Services\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $expenses,
        private ExpenseCategoryRepository $categories
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('expenses.view');
        $filters = $request->only(['date_from','date_to','category_id','payment_method']);
        $list = $this->expenses->listWithFilters($filters, 15);
        $summary = $this->expenses->summary($filters);
        $breakdown = $this->expenses->breakdownByCategory($filters);
        $cats = $this->categories->all();
        // Build labels and data arrays for chart
        $labels = [];
        $data = [];
        foreach ($cats as $c) {
            if (array_key_exists($c->id, $breakdown)) {
                $labels[] = $c->name;
                $data[] = (float) $breakdown[$c->id];
            }
        }
        return view('expenses.index', [
            'list' => $list,
            'filters' => $filters,
            'summary' => $summary,
            'breakdown' => $breakdown,
            'cats' => $cats,
            'breakdownLabels' => $labels,
            'breakdownData' => $data,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('expenses.manage');
        $cats = $this->categories->all();
        return view('expenses.create', compact('cats'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('expenses.manage');
        $data = $request->validate([
            'category_id' => ['required','exists:expense_categories,id'],
            'title' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0.01'],
            'payment_method' => ['required','in:cash,momo,bank'],
            'reference' => ['nullable','string','max:255'],
            'expense_date' => ['required','date'],
        ]);
        $data['created_by'] = $request->user()->id;
        $this->expenses->create($data);
        return redirect()->route('expenses.index')->with('status', 'Expense recorded');
    }

    public function export(Request $request)
    {
        Gate::authorize('expenses.view');
        $filters = $request->only(['date_from','date_to','category_id','payment_method']);
        $query = app(\App\Repositories\ExpenseRepository::class)->queryForFilters($filters);
        $filename = 'expenses_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        return response()->stream(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date','Category','Title','Amount','Method','Recorded By','Reference']);
            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $e) {
                    fputcsv($out, [
                        optional($e->expense_date)->format('Y-m-d'),
                        optional($e->category)->name,
                        $e->title,
                        number_format((float)$e->amount, 2, '.', ''),
                        $e->payment_method,
                        optional($e->creator)->name,
                        $e->reference,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }
}
