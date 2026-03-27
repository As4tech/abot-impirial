<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Point of Sale</h2>
    </x-slot>

    <div class="p-4 space-y-6">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="flex items-center gap-4 border-b mb-4">
                        <button type="button" data-tab="products" class="pos-tab px-3 py-2 border-b-2 border-blue-600 text-blue-700 font-medium">Products</button>
                        <button type="button" data-tab="menu" class="pos-tab px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800">Menu</button>
                        <button type="button" data-tab="rooms" class="pos-tab px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800">Rooms</button>
                    </div>

                    <div id="tab-products" class="tab-panel">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse ($products as $product)
                                <div class="border rounded p-3 flex flex-col justify-between">
                                    <div>
                                        <div class="font-medium">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-600"><x-currency :amount="$product->price" /></div>
                                    </div>
                                    <form method="POST" action="{{ route('pos.cart.add') }}" class="mt-3 flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="number" name="quantity" min="1" value="1" class="w-20 border rounded px-2 py-1" />
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Add</button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-gray-500">No products available.</div>
                            @endforelse
                        </div>
                    </div>

                    <div id="tab-menu" class="tab-panel hidden">
                        @if(isset($menuCategories) && $menuCategories->count())
                            @foreach($menuCategories as $category)
                                <div class="mb-5">
                                    <h4 class="text-md font-semibold mb-2">{{ $category->name }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @forelse ($category->items as $item)
                                            <div class="border rounded p-3 flex flex-col justify-between">
                                                <div>
                                                    <div class="font-medium">{{ $item->name }}</div>
                                                    <div class="text-sm text-gray-600"><x-currency :amount="$item->price" /></div>
                                                </div>
                                                <form method="POST" action="{{ route('pos.cart.addMenu') }}" class="mt-3 flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                                                    <input type="number" name="quantity" min="1" value="1" class="w-20 border rounded px-2 py-1" />
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Add</button>
                                                </form>
                                            </div>
                                        @empty
                                            <div class="text-gray-500">No items in this category.</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-gray-500">No menu categories/items available.</div>
                        @endif
                    </div>

                    <div id="tab-rooms" class="tab-panel hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @php $roomList = ($rooms ?? collect()); @endphp
                            @forelse($roomList as $room)
                                <div class="border rounded p-3 flex flex-col justify-between">
                                    <div>
                                        @php $occupied = in_array(strtolower($room->status ?? ''), ['occupied','booked']); @endphp
                                        <div class="font-medium flex items-center justify-between">
                                            <span>Room {{ $room->room_number }} <span class="text-gray-500 text-sm">({{ $room->type }})</span></span>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $occupied ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                {{ $occupied ? 'Occupied' : 'Vacant' }}
                                            </span>
                                        </div>
                                        @if(isset($room->price))
                                            <div class="text-sm text-gray-600"><x-currency :amount="$room->price" /> / night</div>
                                        @endif
                                        @if(isset($room->hourly_rate))
                                            <div class="text-sm text-gray-600"><x-currency :amount="$room->hourly_rate" /> / hour</div>
                                        @endif
                                        @if(!empty($room->capacity))
                                            <div class="text-xs text-gray-500">Capacity: {{ $room->capacity }}</div>
                                        @endif
                                        @if(!empty($room->amenities))
                                            <div class="text-xs text-gray-500">Amenities: {{ is_array($room->amenities) ? implode(', ', $room->amenities) : $room->amenities }}</div>
                                        @endif
                                        @if(!empty($room->image_path))
                                            <img src="{{ asset('storage/'.$room->image_path) }}" alt="Room {{ $room->room_number }}" class="mt-2 h-24 w-full object-cover rounded">
                                        @endif
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        @php $hasHourly = isset($room->hourly_rate) && $room->hourly_rate > 0; @endphp
                                        @if($hasHourly)
                                            <select class="room-rate-type border rounded px-2 py-1 text-sm" aria-label="Rate Type">
                                                <option value="daily" selected>Daily</option>
                                                <option value="hourly">Hourly</option>
                                            </select>
                                        @endif
                                        <button type="button" data-room-id="{{ $room->id }}" data-daily="{{ $room->price ?? '' }}" data-hourly="{{ $room->hourly_rate ?? '' }}" class="use-room px-3 py-1 rounded text-white {{ $occupied ? 'bg-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700' }}" {{ $occupied ? 'disabled' : '' }}>Use this room</button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-gray-500">No available rooms.</div>
                            @endforelse
                        </div>
                    </div>

                    <script>
                        (function(){
                            const tabs = document.querySelectorAll('.pos-tab');
                            const panels = {
                                products: document.getElementById('tab-products'),
                                menu: document.getElementById('tab-menu'),
                                rooms: document.getElementById('tab-rooms')
                            };
                            tabs.forEach(btn => {
                                btn.addEventListener('click', () => {
                                    const target = btn.getAttribute('data-tab');
                                    // toggle classes
                                    tabs.forEach(b => b.classList.remove('text-blue-700','border-blue-600'));
                                    btn.classList.add('text-blue-700','border-blue-600');
                                    panels.products.classList.toggle('hidden', target !== 'products');
                                    panels.menu.classList.toggle('hidden', target !== 'menu');
                                    panels.rooms.classList.toggle('hidden', target !== 'rooms');
                                });
                            });
                            // quick-select room into checkout form
                            const roomButtons = document.querySelectorAll('.use-room');
                            const orderType = document.getElementById('order_type');
                            const roomSelect = document.querySelector('select[name="room_id"]');
                            const rateTypeInput = document.querySelector('input[name="room_rate_type"]');
                            const ratePriceInput = document.querySelector('input[name="room_rate_price"]');
                            roomButtons.forEach(btn => {
                                btn.addEventListener('click', () => {
                                    const id = btn.getAttribute('data-room-id');
                                    if (orderType) { orderType.value = 'room'; orderType.dispatchEvent(new Event('change')); }
                                    if (roomSelect && id) { roomSelect.value = id; }
                                    // find a sibling rate type select if present
                                    const wrap = btn.closest('.flex');
                                    const rateSel = wrap ? wrap.querySelector('.room-rate-type') : null;
                                    const selType = rateSel ? rateSel.value : 'daily';
                                    if (rateTypeInput) rateTypeInput.value = selType;
                                    const daily = btn.getAttribute('data-daily');
                                    const hourly = btn.getAttribute('data-hourly');
                                    const price = selType === 'hourly' ? (hourly || '') : (daily || '');
                                    if (ratePriceInput) ratePriceInput.value = price;
                                    // focus checkout area
                                    document.getElementById('room_selector_wrap')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                });
                            });
                        })();
                    </script>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Cart</h3>
                    <div class="space-y-3">
                        @forelse ($cart as $line)
                            <div class="border rounded p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium">{{ $line['name'] }}</div>
                                        <div class="text-sm text-gray-600"><x-currency :amount="$line['price']" /></div>
                                    </div>
                                    <form method="POST" action="{{ route('pos.cart.remove') }}">
                                        @csrf
                                        @if (!empty($line['key']))
                                            <input type="hidden" name="key" value="{{ $line['key'] }}">
                                        @else
                                            <input type="hidden" name="product_id" value="{{ $line['product_id'] }}">
                                        @endif
                                        <button class="text-red-600 hover:underline" type="submit">Remove</button>
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('pos.cart.update') }}" class="mt-2 flex items-center gap-2">
                                    @csrf
                                    @if (!empty($line['key']))
                                        <input type="hidden" name="key" value="{{ $line['key'] }}">
                                    @else
                                        <input type="hidden" name="product_id" value="{{ $line['product_id'] }}">
                                    @endif
                                    <label class="text-sm text-gray-600">Qty</label>
                                    <input type="number" name="quantity" min="0" value="{{ $line['quantity'] }}" class="w-20 border rounded px-2 py-1" />
                                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-3 py-1 rounded">Update</button>
                                </form>
                            </div>
                        @empty
                            <div class="text-gray-500">Cart is empty.</div>
                        @endforelse
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span><x-currency :amount="$total" /></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pos.checkout') }}" class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="room_rate_type" value="" />
                        <input type="hidden" name="room_rate_price" value="" />
                        <label class="block text-sm font-medium">Order Type</label>
                        <select id="order_type" name="order_type" class="w-full border rounded px-2 py-2">
                            <option value="walk-in">Walk-in</option>
                            <option value="room">Room</option>
                        </select>
                        <div id="room_selector_wrap" class="space-y-2 hidden">
                            <label class="block text-sm font-medium">Select Room</label>
                            <select name="room_id" class="w-full border rounded px-2 py-2">
                                <option value="">-- choose room --</option>
                                @foreach(($rooms ?? []) as $room)
                                    <option value="{{ $room->id }}">Room {{ $room->room_number }} ({{ $room->type }})</option>
                                @endforeach
                            </select>
                            @if(($rooms ?? collect())->isEmpty())
                                <p class="text-sm text-amber-600">No available rooms.</p>
                            @endif
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" {{ empty($cart) ? 'disabled' : '' }}>Checkout</button>
                    </form>
                    <script>
                        (function(){
                            const select = document.getElementById('order_type');
                            const wrap = document.getElementById('room_selector_wrap');
                            function toggle(){
                                if (!select || !wrap) return;
                                wrap.classList.toggle('hidden', select.value !== 'room');
                            }
                            if (select) {
                                select.addEventListener('change', toggle);
                                toggle();
                            }
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
