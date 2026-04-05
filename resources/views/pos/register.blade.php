<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">POS Register</h2>
            <a href="{{ route('pos.index') }}" class="px-4 py-2 border rounded">Back to POS</a>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-3">Current Register</h3>
                @if ($openRegister)
                    <dl class="text-sm space-y-1">
                        <div class="flex justify-between"><dt>Status</dt><dd class="font-medium text-emerald-700">Open</dd></div>
                        <div class="flex justify-between"><dt>Opened</dt><dd>{{ $openRegister->opened_at->format('Y-m-d H:i') }}</dd></div>
                        <div class="flex justify-between"><dt>Opening Amount</dt><dd><x-currency :amount="$openRegister->opening_amount" /></dd></div>
                        <div class="flex justify-between"><dt>Notes</dt><dd>{{ $openRegister->notes ?? '—' }}</dd></div>
                    </dl>
                    <form action="{{ route('pos.register.close') }}" method="post" class="mt-4 space-y-2">
                        @csrf
                        <label class="block text-sm font-medium">Closing Amount</label>
                        <input type="number" step="0.01" min="0" name="closing_amount" class="w-full border rounded px-2 py-2" required />
                        <label class="block text-sm font-medium">Notes (optional)</label>
                        <textarea name="notes" class="w-full border rounded px-2 py-2" rows="2"></textarea>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Close Register</button>
                    </form>
                @else
                    <p class="text-sm text-gray-600">No open register.</p>
                    <form action="{{ route('pos.register.open') }}" method="post" class="mt-4 space-y-2">
                        @csrf
                        <label class="block text-sm font-medium">Opening Amount</label>
                        <input type="number" step="0.01" min="0" name="opening_amount" class="w-full border rounded px-2 py-2" />
                        <label class="block text-sm font-medium">Notes (optional)</label>
                        <textarea name="notes" class="w-full border rounded px-2 py-2" rows="2"></textarea>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">Open Register</button>
                    </form>
                @endif
            </div>
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-3">Recent Sessions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left uppercase">Opened</th>
                                <th class="px-4 py-2 text-left uppercase">Closed</th>
                                <th class="px-4 py-2 text-left uppercase">Opening</th>
                                <th class="px-4 py-2 text-left uppercase">Closing</th>
                                <th class="px-4 py-2 text-left uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recent as $r)
                                <tr>
                                    <td class="px-4 py-2">{{ $r->opened_at?->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">{{ $r->closed_at?->format('Y-m-d H:i') ?: '—' }}</td>
                                    <td class="px-4 py-2"><x-currency :amount="$r->opening_amount" /></td>
                                    <td class="px-4 py-2">
                                    @if($r->closing_amount !== null)
                                        <x-currency :amount="$r->closing_amount" />
                                    @else
                                        —
                                    @endif
                                </td>
                                    <td class="px-4 py-2">{{ ucfirst($r->status) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-2 text-gray-500">No past sessions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
