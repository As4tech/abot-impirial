<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Repositories\ExpenseCategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    public function __construct(private ExpenseCategoryRepository $categories) {}

    public function index(): View
    {
        $this->authorize('expenses.view');
        $list = $this->categories->paginate(15);
        return view('expenses.categories.index', compact('list'));
    }

    public function create(): View
    {
        $this->authorize('expenses.manage');
        return view('expenses.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('expenses.manage');
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
        ]);
        $this->categories->create($data);
        return redirect()->route('expenses.categories.index')->with('status', 'Category created');
    }

    public function edit(ExpenseCategory $category): View
    {
        $this->authorize('expenses.manage');
        return view('expenses.categories.edit', compact('category'));
    }

    public function update(Request $request, ExpenseCategory $category): RedirectResponse
    {
        $this->authorize('expenses.manage');
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
        ]);
        $this->categories->update($category, $data);
        return redirect()->route('expenses.categories.index')->with('status', 'Category updated');
    }

    public function destroy(ExpenseCategory $category): RedirectResponse
    {
        $this->authorize('expenses.manage');
        try {
            $this->categories->delete($category);
            return redirect()->route('expenses.categories.index')->with('status', 'Category deleted');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
