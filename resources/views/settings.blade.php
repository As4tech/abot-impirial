<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">System Settings</h2>
            <button form="settings-form" class="px-4 py-2 bg-gray-800 text-white rounded">Save Changes</button>
        </div>
    </x-slot>

    <div class="p-6 space-y-4">
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-4 pt-4">
                <div class="inline-flex bg-gray-100 rounded p-1 text-sm">
                    <button class="tab-btn px-3 py-2 rounded data-[active=true]:bg-white data-[active=true]:shadow" data-tab="general">General</button>
                    <button class="tab-btn px-3 py-2 rounded data-[active=true]:bg-white data-[active=true]:shadow" data-tab="pos">POS</button>
                    <button class="tab-btn px-3 py-2 rounded data-[active=true]:bg-white data-[active=true]:shadow" data-tab="inventory">Inventory</button>
                    <button class="tab-btn px-3 py-2 rounded data-[active=true]:bg-white data-[active=true]:shadow" data-tab="rooms">Rooms</button>
                </div>
            </div>

            <form id="settings-form" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="p-6 space-y-10">
                @csrf

                <section class="tab-view" data-tab="general">
                    <h3 class="font-semibold mb-4">General Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium">Business name</label>
                            <input type="text" name="general[business_name]" value="{{ old('general.business_name', $s['general.business_name'] ?? setting('general.business_name')) }}" class="w-full border rounded px-3 py-2" placeholder="Abot Imperial" />
                            <p class="mt-1 text-xs text-gray-500">Shown in page titles, receipts, and emails.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Contact info</label>
                            <input type="text" name="general[contact]" value="{{ old('general.contact', $s['general.contact'] ?? setting('general.contact')) }}" class="w-full border rounded px-3 py-2" placeholder="e.g. +63 900 000 0000, info@example.com" />
                            <p class="mt-1 text-xs text-gray-500">Displayed on receipts if configured.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium">Logo</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-50 border rounded overflow-hidden flex items-center justify-center">
                                    @php $logoUrl = old('general.logo', $s['general.logo'] ?? setting('general.logo')); @endphp
                                    @if ($logoUrl)
                                        <img id="logoPreview" src="{{ $logoUrl }}" alt="Logo preview" class="max-w-full max-h-full" />
                                    @else
                                        <img id="logoPreview" alt="Logo preview" class="max-w-full max-h-full hidden" />
                                        <span class="text-gray-400 text-xs" id="logoPlaceholder">No logo</span>
                                    @endif
                                </div>
                                <input id="logoFileInput" type="file" name="general[logo_file]" accept="image/png,image/jpeg,image/svg+xml" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-100 hover:file:bg-gray-200" />
                            </div>
                            <input type="url" name="general[logo]" value="{{ old('general.logo', $s['general.logo'] ?? setting('general.logo')) }}" class="w-full border rounded px-3 py-2" placeholder="https://.../logo.png" />
                            <p class="mt-1 text-xs text-gray-500">Upload a file or provide a public URL. Uploaded file takes precedence.</p>
                            <label class="mt-2 inline-flex items-center gap-2 text-xs text-gray-600">
                                <input id="removeLogoToggle" type="checkbox" name="general[remove_logo]" value="1" class="rounded border-gray-300" /> Remove logo
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium">Favicon</label>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-50 border rounded overflow-hidden flex items-center justify-center">
                                    @php $faviconUrl = old('general.favicon', $s['general.favicon'] ?? setting('general.favicon')); @endphp
                                    @if ($faviconUrl)
                                        <img id="faviconPreview" src="{{ $faviconUrl }}" alt="Favicon preview" class="max-w-full max-h-full" />
                                    @else
                                        <img id="faviconPreview" alt="Favicon preview" class="max-w-full max-h-full hidden" />
                                        <span class="text-gray-400 text-[10px]" id="faviconPlaceholder">No icon</span>
                                    @endif
                                </div>
                                <input id="faviconFileInput" type="file" name="general[favicon_file]" accept="image/x-icon,image/png" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-100 hover:file:bg-gray-200" />
                            </div>
                            <input type="url" name="general[favicon]" value="{{ old('general.favicon', $s['general.favicon'] ?? setting('general.favicon')) }}" class="w-full border rounded px-3 py-2" placeholder="https://.../favicon.ico" />
                            <p class="mt-1 text-xs text-gray-500">Upload an .ico or .png, or provide a URL. Uploaded file takes precedence.</p>
                            <label class="mt-2 inline-flex items-center gap-2 text-xs text-gray-600">
                                <input id="removeFaviconToggle" type="checkbox" name="general[remove_favicon]" value="1" class="rounded border-gray-300" /> Remove favicon
                            </label>
                        </div>
                    </div>
                </section>

                <section class="tab-view hidden" data-tab="pos">
                    <h3 class="font-semibold mb-4">POS Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium">Default tax rate (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="pos[tax_rate]" value="{{ old('pos.tax_rate', $s['pos.tax_rate'] ?? setting('pos.tax_rate', 0)) }}" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Currency</label>
                            <input type="text" name="pos[currency]" value="{{ old('pos.currency', $s['pos.currency'] ?? setting('pos.currency', 'PHP')) }}" class="w-full border rounded px-3 py-2" placeholder="e.g. PHP, USD" />
                            <p class="mt-1 text-xs text-gray-500">Three-letter code used across receipts and reports.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Receipt footer message</label>
                            <textarea name="pos[receipt_footer]" rows="3" class="w-full border rounded px-3 py-2" placeholder="Thank you for your purchase!">{{ old('pos.receipt_footer', $s['pos.receipt_footer'] ?? setting('pos.receipt_footer')) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Supports multiple lines. Appears on printed receipts.</p>
                        </div>
                    </div>
                </section>

                <section class="tab-view hidden" data-tab="inventory">
                    <h3 class="font-semibold mb-4">Inventory Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium">Low stock threshold</label>
                            <input type="number" min="0" name="inventory[low_stock_threshold]" value="{{ old('inventory.low_stock_threshold', $s['inventory.low_stock_threshold'] ?? setting('inventory.low_stock_threshold', config('inventory.threshold', 5))) }}" class="w-full border rounded px-3 py-2" />
                        </div>
                    </div>
                </section>

                <section class="tab-view hidden" data-tab="rooms">
                    <h3 class="font-semibold mb-4">Room Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium">Default check-in time</label>
                            <input type="time" name="rooms[check_in_time]" value="{{ old('rooms.check_in_time', $s['rooms.check_in_time'] ?? setting('rooms.check_in_time', '14:00')) }}" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Default check-out time</label>
                            <input type="time" name="rooms[check_out_time]" value="{{ old('rooms.check_out_time', $s['rooms.check_out_time'] ?? setting('rooms.check_out_time', '12:00')) }}" class="w-full border rounded px-3 py-2" />
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <script>
        (function(){
            const btns = document.querySelectorAll('.tab-btn');
            const views = document.querySelectorAll('.tab-view');
            function activate(tab){
                btns.forEach(b=>b.dataset.active = (b.dataset.tab===tab));
                views.forEach(v=>{
                    if(v.dataset.tab===tab){ v.classList.remove('hidden'); }
                    else { v.classList.add('hidden'); }
                });
                localStorage.setItem('settings.activeTab', tab);
            }
            btns.forEach(b=> b.addEventListener('click', ()=>activate(b.dataset.tab)));
            activate(localStorage.getItem('settings.activeTab') || 'general');
        })();

        (function(){
            const logoInput = document.getElementById('logoFileInput');
            const logoPreview = document.getElementById('logoPreview');
            const logoPlaceholder = document.getElementById('logoPlaceholder');
            const removeLogo = document.getElementById('removeLogoToggle');
            if (logoInput) {
                logoInput.addEventListener('change', (e)=>{
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    if (logoPreview) {
                        logoPreview.src = url;
                        logoPreview.classList.remove('hidden');
                    }
                    if (logoPlaceholder) logoPlaceholder.classList.add('hidden');
                    if (removeLogo) removeLogo.checked = false;
                });
            }
            if (removeLogo && logoPreview && logoPlaceholder) {
                removeLogo.addEventListener('change', ()=>{
                    if (removeLogo.checked) {
                        logoPreview.classList.add('hidden');
                        logoPlaceholder.classList.remove('hidden');
                    }
                });
            }

            const favInput = document.getElementById('faviconFileInput');
            const favPreview = document.getElementById('faviconPreview');
            const favPlaceholder = document.getElementById('faviconPlaceholder');
            const removeFav = document.getElementById('removeFaviconToggle');
            if (favInput) {
                favInput.addEventListener('change', (e)=>{
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    if (favPreview) {
                        favPreview.src = url;
                        favPreview.classList.remove('hidden');
                    }
                    if (favPlaceholder) favPlaceholder.classList.add('hidden');
                    if (removeFav) removeFav.checked = false;
                });
            }
            if (removeFav && favPreview && favPlaceholder) {
                removeFav.addEventListener('change', ()=>{
                    if (removeFav.checked) {
                        favPreview.classList.add('hidden');
                        favPlaceholder.classList.remove('hidden');
                    }
                });
            }
        })();
    </script>
</x-app-layout>
