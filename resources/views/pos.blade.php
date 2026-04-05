<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Point of Sale</h2>
            <div class="flex items-center gap-2">
                @if (!empty($openRegister))
                    <a href="{{ route('pos.register.index') }}" class="inline-flex items-center gap-2 text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Close Register</span>
                    </a>
                @else
                    <a href="{{ route('pos.register.index') }}" class="inline-flex items-center gap-2 text-sm bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                        <span>Register</span>
                    </a>
                @endif
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h8m-8 4h5M4 6h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                    <span>Go to Active Stays</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4 space-y-6">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:max-w-md">
                            <label for="pos-search" class="block text-sm font-medium text-gray-700 mb-1">Search POS</label>
                            <input id="pos-search" type="text" placeholder="Search product, menu item, or room" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div id="pos-search-summary" class="text-sm text-gray-500">Showing all items</div>
                    </div>

                    <div class="flex items-center gap-4 border-b mb-4">
                        <button type="button" data-tab="products" class="pos-tab px-3 py-2 border-b-2 border-blue-600 text-blue-700 font-medium">Products</button>
                        <button type="button" data-tab="menu" class="pos-tab px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800">Menu</button>
                        <button type="button" data-tab="rooms" class="pos-tab px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800">Rooms</button>
                    </div>

                    <div id="tab-products" class="tab-panel">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse ($products as $product)
                                <div class="border rounded p-3 flex flex-col justify-between pos-search-item" data-search-category="product" data-search-text="{{ strtolower(trim($product->name.' '.$product->price)) }}">
                                    <div>
                                        @if(!empty($product->image_path))
                                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="mb-2 h-24 w-full object-cover rounded" />
                                        @else
                                            <div class="mb-2 h-24 w-full rounded bg-gray-50 border flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                        @endif
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
                                <div class="mb-5 pos-menu-category" data-category-name="{{ strtolower(trim($category->name)) }}">
                                    <h4 class="text-md font-semibold mb-2">{{ $category->name }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @forelse ($category->items as $item)
                                            <div class="border rounded p-3 flex flex-col justify-between pos-search-item" data-search-category="menu" data-search-text="{{ strtolower(trim($category->name.' '.$item->name.' '.$item->price)) }}">
                                                <div>
                                                    @if(!empty($item->image_path))
                                                        <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="mb-2 h-24 w-full object-cover rounded" />
                                                    @endif
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
                                <div class="border rounded p-3 flex flex-col justify-between pos-search-item" data-search-category="room" data-search-text="{{ strtolower(trim('room '.$room->room_number.' '.$room->type.' '.($room->stay_type ?? 'long').' '.($room->status ?? '').' '.($room->long_price ?? $room->price).' '.($room->short_price ?? $room->price))) }}">
                                    <div>
                                        @php $occupied = in_array(strtolower($room->status ?? ''), ['occupied','booked']); @endphp
                                        <div class="font-medium flex items-center justify-between">
                                            <span>Room {{ $room->room_number }} <span class="text-gray-500 text-sm">({{ $room->type }})</span></span>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $occupied ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                {{ $occupied ? 'Occupied' : 'Vacant' }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600"><x-currency :amount="$room->long_price ?? $room->price" /> / long</div>
                                        <div class="text-sm text-gray-600"><x-currency :amount="$room->short_price ?? $room->price" /> / short</div>
                                        @if(!empty($room->capacity))
                                            <div class="text-xs text-gray-500">Capacity: {{ $room->capacity }}</div>
                                        @endif
                                        @php
                                            $features = [];
                                            if ($room->has_ac) $features[] = 'AC';
                                            if ($room->has_fan) $features[] = 'Fan';
                                            if ($room->has_tv) $features[] = 'TV';
                                            if ($room->has_fridge) $features[] = 'Fridge';
                                            if ($room->bed_type) $features[] = $room->bed_type;
                                        @endphp
                                        @if(count($features))
                                            <div class="text-xs text-gray-500">{{ implode(', ', $features) }}</div>
                                        @endif
                                        @if(!empty($room->image_path))
                                            <img src="{{ $room->image_path }}" alt="Room {{ $room->room_number }}" class="mt-2 h-24 w-full object-cover rounded">
                                        @else
                                            <div class="mt-2 h-24 w-full rounded bg-gray-50 border flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <select class="room-rate-type border rounded px-2 py-1 text-sm" aria-label="Rate Type">
                                            <option value="long" @selected(($room->stay_type ?? 'long') === 'long')>Long</option>
                                            <option value="short" @selected(($room->stay_type ?? '') === 'short')>Short</option>
                                        </select>
                                        <button type="button" data-room-id="{{ $room->id }}" data-long="{{ $room->long_price ?? $room->price ?? '' }}" data-short="{{ $room->short_price ?? $room->price ?? '' }}" class="use-room px-3 py-1 rounded text-white {{ $occupied ? 'bg-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700' }}" {{ $occupied ? 'disabled' : '' }}>Use this room</button>
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
                            const searchInput = document.getElementById('pos-search');
                            const searchSummary = document.getElementById('pos-search-summary');
                            const searchItems = document.querySelectorAll('.pos-search-item');
                            const menuCategories = document.querySelectorAll('.pos-menu-category');
                            const panels = {
                                products: document.getElementById('tab-products'),
                                menu: document.getElementById('tab-menu'),
                                rooms: document.getElementById('tab-rooms')
                            };
                            function updateSearchSummary(query) {
                                if (!searchSummary) return;
                                const visible = Array.from(searchItems).filter(item => !item.classList.contains('hidden')).length;
                                if (!query) {
                                    searchSummary.textContent = 'Showing all items';
                                    return;
                                }
                                searchSummary.textContent = 'Found ' + visible + ' matching item' + (visible === 1 ? '' : 's');
                            }
                            function filterItems() {
                                const query = (searchInput?.value || '').trim().toLowerCase();
                                searchItems.forEach(item => {
                                    const haystack = item.getAttribute('data-search-text') || '';
                                    item.classList.toggle('hidden', query !== '' && !haystack.includes(query));
                                });
                                menuCategories.forEach(category => {
                                    const categoryItems = category.querySelectorAll('.pos-search-item');
                                    const hasVisibleItems = Array.from(categoryItems).some(item => !item.classList.contains('hidden'));
                                    const categoryName = category.getAttribute('data-category-name') || '';
                                    const matchesCategory = query !== '' && categoryName.includes(query);
                                    if (matchesCategory) {
                                        categoryItems.forEach(item => item.classList.remove('hidden'));
                                    }
                                    category.classList.toggle('hidden', query !== '' && !matchesCategory && !hasVisibleItems);
                                });
                                updateSearchSummary(query);
                            }
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
                            if (searchInput) {
                                searchInput.addEventListener('input', filterItems);
                                filterItems();
                            }
                            // quick-select room into checkout form
                            const roomButtons = document.querySelectorAll('.use-room');
                            roomButtons.forEach(btn => {
                                btn.addEventListener('click', () => {
                                    // fetch checkout elements at click time to ensure they exist
                                    const orderType = document.getElementById('order_type');
                                    const roomSelect = document.querySelector('select[name="room_id"]');
                                    const rateTypeInput = document.querySelector('input[name="room_rate_type"]');
                                    const ratePriceInput = document.querySelector('input[name="room_rate_price"]');
                                    const id = btn.getAttribute('data-room-id');
                                    if (orderType) {
                                        orderType.value = 'room';
                                        orderType.dispatchEvent(new Event('change'));
                                    }
                                    if (roomSelect && id) { roomSelect.value = id; }
                                    // find a sibling rate type select if present
                                    const wrap = btn.closest('.flex');
                                    const rateSel = wrap ? wrap.querySelector('.room-rate-type') : null;
                                    const selType = rateSel ? rateSel.value : 'long';
                                    if (rateTypeInput) rateTypeInput.value = selType;
                                    const longPrice = btn.getAttribute('data-long');
                                    const shortPrice = btn.getAttribute('data-short');
                                    const price = selType === 'short' ? (shortPrice || '') : (longPrice || '');
                                    if (ratePriceInput) ratePriceInput.value = price;
                                    // ensure selector is visible even if change event didn't fire
                                    const selectorWrap = document.getElementById('room_selector_wrap');
                                    if (selectorWrap) {
                                        selectorWrap.classList.remove('hidden');
                                        selectorWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    }
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

                    <form method="POST" action="{{ route('pos.checkout') }}" class="mt-4 space-y-3" id="pos_checkout_form">
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
                            <div>
                                <label class="block text-sm font-medium">Stay Type</label>
                                <select id="room_rate_type_select" class="w-full border rounded px-2 py-2">
                                    <option value="long">Long</option>
                                    <option value="short">Short</option>
                                </select>
                            </div>
                        </div>
                        <button id="checkout_btn" type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Checkout</button>
                    </form>
                    <script>
                        (function(){
                            const select = document.getElementById('order_type');
                            const wrap = document.getElementById('room_selector_wrap');
                            const rateTypeInput = document.querySelector('input[name="room_rate_type"]');
                            const ratePriceInput = document.querySelector('input[name="room_rate_price"]');
                            const rateTypeSelect = document.getElementById('room_rate_type_select');
                            const roomSelect = document.querySelector('select[name="room_id"]');
                            const checkoutBtn = document.getElementById('checkout_btn');
                            const roomButtons = document.querySelectorAll('.use-room');
                            function syncRoomRateFromSelection() {
                                if (!roomSelect || !rateTypeSelect || !rateTypeInput || !ratePriceInput) return;
                                const selectedRoomId = roomSelect.value;
                                if (!selectedRoomId) return;
                                const activeButton = Array.from(roomButtons).find(btn => btn.getAttribute('data-room-id') === selectedRoomId);
                                if (!activeButton) return;
                                const selectedType = rateTypeSelect.value;
                                rateTypeInput.value = selectedType;
                                ratePriceInput.value = selectedType === 'short'
                                    ? (activeButton.getAttribute('data-short') || '')
                                    : (activeButton.getAttribute('data-long') || '');
                            }
                            function toggle(){
                                if (!select || !wrap) return;
                                const isRoom = select.value === 'room';
                                wrap.classList.toggle('hidden', !isRoom);
                                if (checkoutBtn) checkoutBtn.textContent = isRoom ? 'Check-in' : 'Checkout';
                                if (checkoutBtn) checkoutBtn.disabled = false;
                            }
                            if (select) {
                                select.addEventListener('change', toggle);
                                toggle();
                            }
                            if (roomSelect) {
                                roomSelect.addEventListener('change', syncRoomRateFromSelection);
                            }
                            if (rateTypeSelect) {
                                rateTypeSelect.addEventListener('change', syncRoomRateFromSelection);
                            }
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
