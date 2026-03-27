<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $suppliers = Supplier::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
        return view('inventory.suppliers.index', compact('suppliers', 'search'));
    }

    public function create(): View
    {
        return view('inventory.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
        ]);
        Supplier::create($validated);
        return redirect()->route('inventory.suppliers.index')->with('status', 'Supplier created');
    }

    public function edit(Supplier $supplier): View
    {
        return view('inventory.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
        ]);
        $supplier->update($validated);
        return redirect()->route('inventory.suppliers.index')->with('status', 'Supplier updated');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return back()->with('status', 'Supplier deleted');
    }
}
