<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports & Analytics</h2>
            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm">
                Last updated: {{ now()->format('M d, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- KPI Cards --}}
            @isset($profit)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-white to-gray-50 shadow-sm rounded-xl border border-gray-100 p-5 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Sales</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><x-currency :amount="$totalSales ?? 0" /></p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-gray-500">
                        <span>Period: Last {{ $days }} days</span>
                    </div>
                </div>
                @endcan

                <div class="bg-gradient-to-br from-white to-gray-50 shadow-sm rounded-xl border border-gray-100 p-5 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Expenses</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><x-currency :amount="$totalExpenses ?? 0" /></p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-gray-500">
                        <span>Period: Last {{ $days }} days</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-white to-gray-50 shadow-sm rounded-xl border border-gray-100 p-5 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Net Profit</p>
                            <p class="mt-2 text-3xl font-bold {{ ($profit ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                <x-currency :amount="$profit ?? 0" />
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-gray-500">
                        <span>Profit = Sales - Expenses</span>
                    </div>
                </div>
            </div>
            @endisset

            {{-- Reports Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Sales Reports --}}
                @can('reports.sales.view')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/40">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Sales Reports</h3>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('reports.daily') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-indigo-600">Daily Sales</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('reports.menu_items') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-indigo-600">Menu Items Sales</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('reports.products') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-indigo-600">Product Sales</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('reports.products_profit') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-indigo-600">Product Profit</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endcan

                {{-- Inventory Reports --}}
                @can('reports.inventory.view')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/40">
                        <div class="flex items-center">
                            <div class="p-2 bg-amber-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Inventory</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <a href="{{ route('reports.inventory') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-amber-600">Inventory Movement</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endcan

                {{-- Bookings Reports --}}
                @can('reports.bookings.view')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/40">
                        <div class="flex items-center">
                            <div class="p-2 bg-teal-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Bookings</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <a href="{{ route('reports.bookings') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-teal-600">Booking Activity</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endcan

                {{-- Registers Reports --}}
                @can('reports.registers.view')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/40">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Registers</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <a href="{{ route('reports.registers') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-purple-600">Register Sessions</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endcan

                {{-- Expenses Reports --}}
                @can('expenses.view')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/40">
                        <div class="flex items-center">
                            <div class="p-2 bg-rose-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Expenses</h3>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('expenses.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-rose-600">Expense List & Summary</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @can('expenses.manage')
                        <a href="{{ route('expenses.create') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition group">
                            <span class="text-gray-700 group-hover:text-rose-600">Record Expense</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                        @endcan
                    </div>
                    <div class="px-5 py-3 bg-gray-50/60 border-t border-gray-100">
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Profit = Total Sales - Total Expenses
                        </p>
                    </div>
                </div>

                {{-- Empty placeholder for grid balance (optional) --}}
                <div class="hidden lg:block"></div>
            </div>
        </div>
    </div>
</x-app-layout>