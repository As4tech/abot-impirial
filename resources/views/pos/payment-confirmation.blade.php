<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payment Confirmation</h2>
            <a href="{{ route('pos.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
    </x-slot>
<div class="p-4 sm:p-6 max-w-2xl mx-auto">
    @if(session('status'))
        @if(str_contains(session('status'), 'Payment of'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="font-medium">Payment Recorded Successfully!</div>
                    <div class="text-sm">{{ session('status') }}</div>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <div class="font-medium">Order Created!</div>
                    <div class="text-sm">{{ session('status') }}</div>
                </div>
            </div>
        @endif
    @endif

    <!-- Payment Summary Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if($order->payments->where('status', 'paid')->count() > 0)
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Payment Confirmed</h3>
                        <p class="text-green-100">Order #{{ $order->id }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Order Created</h3>
                        <p class="text-blue-100">Order #{{ $order->id }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6">
            <!-- Order Details -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Order Details</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">Order Number:</span>
                            <div class="font-medium">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Date:</span>
                            <div class="font-medium">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Total Amount:</span>
                            <div class="font-medium text-lg">{{ $currency }} {{ number_format($order->total_amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Status:</span>
                            @if($order->payments->where('status', 'paid')->count() > 0)
                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Paid
                                </div>
                            @else
                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending Payment
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Payment Information</h4>
                @if($order->payments->where('status', 'paid')->count() > 0)
                    <div class="space-y-3">
                        @foreach($order->payments->where('status', 'paid') as $payment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        @if($payment->method === 'cash')
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</div>
                                        <div class="text-sm text-gray-600">{{ $payment->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-lg">{{ $currency }} {{ number_format($payment->amount, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <div class="font-medium">No Payment Recorded</div>
                            <div class="text-sm">Please record payment using the form below to complete this transaction.</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Payment Recording Form (only show if no payments recorded) -->
            @if($order->payments->where('status', 'paid')->count() == 0)
                <div class="bg-gray-50 rounded-lg p-6 border-2 border-dashed border-gray-300">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Record Payment
                    </h4>
                    
                    <form method="POST" action="{{ route('pos.payments.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select name="method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select method</option>
                                    <option value="cash">Cash</option>
                                    <option value="mobile_money">Mobile Money</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="paid" selected>Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <input type="number" name="amount" step="0.01" min="0.01" 
                                   value="{{ number_format($order->total_amount, 2, '.', '') }}" 
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Total order amount: {{ $currency }} {{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Record Payment
                        </button>
                    </form>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('pos.receipt', $order) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/>
                    </svg>
                    View & Print Receipt
                </a>
                
                <a href="{{ route('pos.receipt.thermal', $order) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Thermal Receipt
                </a>
            </div>

            <!-- Additional Actions -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('pos.index') }}" class="flex-1 text-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                        Back to POS
                    </a>
                    
                    @if($order->room_id)
                        <a href="{{ route('bookings.checkout', $order->room_id) }}" class="flex-1 text-center bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Checkout Room
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
