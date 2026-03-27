<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restaurant</h2>
    </x-slot>
    <div class="p-6">
        @if (session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @role('Admin|Manager')
            <a href="{{ route('menu-categories.index') }}" class="block bg-white rounded-lg shadow-sm border hover:shadow transition p-5">
                <div class="text-gray-500 text-sm">Restaurant</div>
                <div class="mt-1 text-lg font-semibold">Menu Categories</div>
                <div class="mt-2 text-sm text-gray-600">Create and organize categories for the restaurant menu.</div>
            </a>

            <a href="{{ route('menu-items.index') }}" class="block bg-white rounded-lg shadow-sm border hover:shadow transition p-5">
                <div class="text-gray-500 text-sm">Restaurant</div>
                <div class="mt-1 text-lg font-semibold">Menu Items</div>
                <div class="mt-2 text-sm text-gray-600">Add items with prices and assign them to categories.</div>
            </a>
            @endrole

            @role('Admin|Manager|Cashier')
            <a href="{{ route('pos.index') }}" class="block bg-white rounded-lg shadow-sm border hover:shadow transition p-5">
                <div class="text-gray-500 text-sm">Sales</div>
                <div class="mt-1 text-lg font-semibold">Point of Sale</div>
                <div class="mt-2 text-sm text-gray-600">Sell products and menu items. Supports room orders.</div>
            </a>
            @endrole

            @role('Admin|Manager|Kitchen Staff')
            <a href="{{ route('kitchen.index') }}" class="block bg-white rounded-lg shadow-sm border hover:shadow transition p-5">
                <div class="text-gray-500 text-sm">Kitchen</div>
                <div class="mt-1 text-lg font-semibold">KOT Screen</div>
                <div class="mt-2 text-sm text-gray-600">Track orders: Pending → Preparing → Served.</div>
            </a>
            @endrole
        </div>

        <div class="mt-6 text-sm text-gray-600">
            <p>
                Tip: Use <a href="{{ route('menu-categories.index') }}" class="text-blue-600 hover:underline">Menu Categories</a> and
                <a href="{{ route('menu-items.index') }}" class="text-blue-600 hover:underline">Menu Items</a> to set up the menu. Then process sales in the
                <a href="{{ route('pos.index') }}" class="text-blue-600 hover:underline">POS</a> and manage preparation in the
                <a href="{{ route('kitchen.index') }}" class="text-blue-600 hover:underline">Kitchen</a>.
            </p>
        </div>
    </div>
</x-app-layout>
