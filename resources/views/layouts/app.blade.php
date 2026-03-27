<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php($appName = function_exists('setting') ? setting('general.business_name', config('app.name', 'Laravel')) : config('app.name', 'Laravel'))
        <title>{{ $appName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if(function_exists('setting') && setting('general.favicon'))
            <link rel="icon" type="image/x-icon" href="{{ setting('general.favicon') }}">
        @endif
        <style>
            details[open] .chevron { transform: rotate(180deg); }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <div class="flex">
                <aside class="w-64 bg-slate-900 text-slate-100 min-h-screen hidden md:block shadow-lg">
                    <div class="px-4 py-5 text-lg font-semibold tracking-wide border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            @if(function_exists('setting') && setting('general.logo'))
                                <img src="{{ setting('general.logo') }}" alt="Logo" class="h-6 w-auto">
                            @endif
                            <span>{{ $appName }}</span>
                        </div>
                    </div>
                    <nav class="px-3 py-3 space-y-1 text-sm">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 pb-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M4.5 10.5V21h6v-6h3v6h6v-10.5"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('pos.index') }}" class="flex items-center gap-3 pt-4 border-b border-slate-800 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('pos.*') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2M3 7v12a2 2 0 002 2h14a2 2 0 002-2V7M8 13h8m-8 4h5"/></svg>
                            <span>POS</span>
                        </a>
                        <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('rooms.*') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a2 2 0 012-2h9.5a2 2 0 012 2v16M4 21h16M10 21v-6h4v6"/></svg>
                            <span>Rooms</span>
                        </a>
                        <a href="{{ route('restaurant.index') }}" class="flex items-center gap-3 px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('restaurant.index') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 3v7a2 2 0 102 2h2V3M12 3v9a3 3 0 106 0V3"/></svg>
                            <span>Restaurant</span>
                        </a>
                        <details class="px-1" @if (request()->is('inventory*')) open @endif>
                            <summary class="flex items-center justify-between px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 cursor-pointer select-none text-slate-200 {{ request()->is('inventory*') ? 'bg-slate-800 text-white' : '' }}">
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"/></svg>
                                    Inventory
                                </span>
                                <svg class="chevron h-4 w-4 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                            </summary>
                            <nav class="ml-4 mt-1 mb-2 space-y-1">
                                <a href="{{ route('inventory.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('inventory.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                    <span>Categories</span>
                                </a>
                                <a href="{{ route('inventory.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('inventory.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7l5-3 5 3v6l-5 3-5-3V7z"/></svg>
                                    <span>Products</span>
                                </a>
                                <a href="{{ route('inventory.movements.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('inventory.movements.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5 5 5M7 13l5 5 5-5"/></svg>
                                    <span>Movements</span>
                                </a>
                                <a href="{{ route('inventory.suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('inventory.suppliers.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17h3l3 3h8a2 2 0 002-2v-4l-4-4H9l-3 3H3v4z"/></svg>
                                    <span>Suppliers</span>
                                </a>
                                <a href="{{ route('inventory.purchases.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('inventory.purchases.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h7M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H7l-2 3H3v15a3 3 0 003 3z"/></svg>
                                    <span>Purchases</span>
                                </a>
                            </nav>
                        </details>
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V8m5 8V5m5 11v-6"/></svg>
                            <span>Reports</span>
                        </a>
                        @can('expenses.view')
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('expenses.*') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M8 11h8m-8 4h5"/></svg>
                            <span>Expenses</span>
                        </a>
                        @endcan
                        @if(auth()->user()->can('manage-users') || auth()->user()->can('manage-roles') || auth()->user()->can('manage-permissions'))
                        <details class="px-1" @if (request()->routeIs('admin.*')) open @endif>
                            <summary class="flex items-center justify-between px-3 pt-4 border-b border-slate-700 py-2 rounded-md hover:bg-slate-800 cursor-pointer select-none text-slate-200 {{ request()->routeIs('admin.*') ? 'bg-slate-800 text-white' : '' }}">
                                <span class="flex items-center gap-3">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v3h7V6a2 2 0 00-2-2H7a2 2 0 00-2 2v3m0 0H3v11a2 2 0 002 2h14a2 2 0 002-2V9h-2m-14 0h14"/></svg>
                                    Administration
                                </span>
                                <svg class="chevron h-4 w-4 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                            </summary>
                            <nav class="ml-4 mt-1 mb-2 space-y-1">
                                @can('manage-users')
                                <a href="{{ route('admin.users.roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('admin.users.roles.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a3.5 3.5 0 117 0m-3.5-7a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM17 11l2 2 4-4"/></svg>
                                    <span>User Roles</span>
                                </a>
                                @endcan
                                @can('manage-roles')
                                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('admin.roles.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v6c0 4-3 7-7 8-4-1-7-4-7-8V7l7-4z"/></svg>
                                    <span>Roles</span>
                                </a>
                                @endcan
                                @can('manage-permissions')
                                <a href="{{ route('admin.permissions.matrix') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('admin.permissions.matrix*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M4 6h16M4 12h6m-6 6h10"/></svg>
                                    <span>Permissions Matrix</span>
                                </a>
                                @endcan
                            </nav>
                        </details>
                        @endif
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 pt-4 border-b border-slate-800 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-white' : 'text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zm7.5-3a7.5 7.5 0 01-.2 1.8l2.1 1.6-2 3.4-2.5-1a7.7 7.7 0 01-1.6 1l-.4 2.7H9.1l-.4-2.7a7.7 7.7 0 01-1.6-1l-2.5 1-2-3.4 2.1-1.6A7.5 7.5 0 014.5 12c0-.6.1-1.2.2-1.8L2.6 8.6l2-3.4 2.5 1c.5-.4 1-.7 1.6-1l.4-2.7h5.8l.4 2.7c.6.3 1.1.6 1.6 1l2.5-1 2 3.4-2.1 1.6c.1.6.2 1.2.2 1.8z"/></svg>
                            <span>Settings</span>
                        </a>
                    </nav>
                </aside>

                <div class="flex-1">
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="p-4">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
