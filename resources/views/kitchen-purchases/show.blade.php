<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kitchen Purchase Details</h2>
            <a href="{{ route('kitchen-purchases.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="p-4 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Purchase Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Purchase ID:</span>
                            <span class="font-medium">#{{ $purchase->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ingredient:</span>
                            <span class="font-medium">{{ $purchase->ingredient?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quantity:</span>
                            <span class="font-medium">{{ number_format($purchase->quantity, 3) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Unit Cost:</span>
                            <span class="font-medium"><x-currency :amount="$purchase->unit_cost" /></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Cost:</span>
                            <span class="font-medium text-lg"><x-currency :amount="$purchase->quantity * $purchase->unit_cost" /></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference Type:</span>
                            <span class="font-medium">{{ $purchase->reference_type ? class_basename($purchase->reference_type) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference ID:</span>
                            <span class="font-medium">{{ $purchase->reference_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Notes:</span>
                            <span class="font-medium">{{ $purchase->notes ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Recorded By:</span>
                            <span class="font-medium">{{ $purchase->user?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ $purchase->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">{{ ucfirst($purchase->type) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($purchase->notes)
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Notes</h3>
                <p class="text-gray-600">{{ $purchase->notes }}</p>
            </div>
            @endif

            <div class="mt-6 pt-6 border-t flex justify-end gap-3">
                <a href="{{ route('kitchen-purchases.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
