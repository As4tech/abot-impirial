<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Record Expense</h2>
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 border rounded">Back to Expenses</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('expenses.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select category</option>
                        @foreach($cats as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" placeholder="Electricity Bill" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Amount</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Payment method</label>
                    <select name="payment_method" class="w-full border rounded px-3 py-2" required>
                        <option value="cash" @selected(old('payment_method')==='cash')>Cash</option>
                        <option value="momo" @selected(old('payment_method')==='momo')>Mobile Money</option>
                        <option value="bank" @selected(old('payment_method')==='bank')>Bank</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Reference (optional)</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" class="w-full border rounded px-3 py-2" placeholder="Receipt #... or note" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="md:col-span-2">
                    <button class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded">Save Expense</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
