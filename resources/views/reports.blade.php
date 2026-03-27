<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
    </x-slot>
    <div class="p-6 space-y-4">
        @isset($profit)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">Total Sales ({{ $days }}d)</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$totalSales ?? 0" /></div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">Total Expenses ({{ $days }}d)</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$totalExpenses ?? 0" /></div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="text-xs uppercase text-gray-500">Profit ({{ $days }}d)</div>
                <div class="mt-1 text-2xl font-bold"><x-currency :amount="$profit ?? 0" /></div>
            </div>
        </div>
        @endisset
        <div class="bg-white shadow-sm rounded-lg p-4">
            <div class="font-semibold mb-2">Sales</div>
            <div class="space-x-2">
                <a href="{{ route('reports.daily') }}" class="px-4 py-2 border rounded">Daily Sales</a>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4">
            <div class="font-semibold mb-2">Inventory</div>
            <div class="space-x-2">
                <a href="{{ route('reports.inventory') }}" class="px-4 py-2 border rounded">Inventory Movement</a>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4">
            <div class="font-semibold mb-2">Expenses</div>
            <div class="space-x-2">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2 border rounded">Expense List & Summary</a>
                @can('expenses.manage')
                <a href="{{ route('expenses.create') }}" class="px-4 py-2 border rounded">Record Expense</a>
                @endcan
            </div>
            <p class="text-xs text-gray-500 mt-2">Profit in financial reporting is calculated as Total Sales minus Total Expenses.</p>
        </div>
    </div>
</x-app-layout>
