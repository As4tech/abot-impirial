<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php($appName = function_exists('setting') ? setting('general.business_name', config('app.name', 'Laravel')) : config('app.name', 'Laravel'))
    <title>{{ $appName }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(function_exists('setting') && setting('general.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ setting('general.favicon') }}">
    @endif

    <style>
        :root {
            --forest:    #1a2e1e;
            --forest-d:  #111f14;
            --moss:      #2e4a33;
            --sage:      #5a7a5e;
            --terra:     #c4784a;
            --terra-lt:  #d9956d;
            --ivory:     #f8f4ee;
            --cream:     #f2ece2;
            --linen:     #e8dfd0;
            --ink:       #1e1a17;
            --ink-lt:    #9a9088;
            --border:    #ddd5c8;
            --gold:      #b89a60;
            --gold-lt:   #d4b87a;
            --sidebar-w: 15rem;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ══ LAYOUT ═══════════════════════════════════════════ */
        .layout-root { display: flex; min-height: 100vh; }

        /* ══ SIDEBAR ══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--forest-d);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
            overflow: hidden;
            transition: transform 0.28s ease;
        }

        /* Gold top accent line */
        .sidebar::before {
            content: '';
            display: block;
            height: 2px;
            background: linear-gradient(90deg, var(--terra) 0%, var(--gold) 50%, transparent 100%);
            flex-shrink: 0;
        }

        /* Subtle radial glow */
        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 40%;
            background: radial-gradient(ellipse 80% 60% at 50% 100%, rgba(184,154,96,0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 1.2rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            text-decoration: none;
        }

        .sidebar-brand img {
            height: 1.6rem;
            width: auto;
            filter: brightness(0) invert(1);
            opacity: 0.8;
            flex-shrink: 0;
        }

        .sidebar-brand-fallback {
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--terra) 0%, var(--gold) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-fallback svg {
            width: 1rem;
            height: 1rem;
            stroke: #fff;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-brand-name {
            font-family: 'Plus Jakarta Sans', serif;
            font-size: 1rem;
            font-weight: 400;
            color: #ffffff;
            letter-spacing: 0.08em;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Nav scroll area ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.85rem 0.75rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.08) transparent;
            position: relative;
            z-index: 1;
        }

        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 2px; }

        /* ── Section labels ── */
        .sidebar-section-label {
            font-size: 0.55rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.78);
            font-weight: 500;
            padding: 0.9rem 0.75rem 0.35rem;
            display: block;
        }

        .sidebar-section-label:first-child { padding-top: 0.1rem; }

        /* ── Nav links ── */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.52rem 0.75rem;
            border-radius: 7px;
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.02em;
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            transition: background 0.16s, color 0.16s;
            position: relative;
            white-space: nowrap;
            margin-bottom: 0.1rem;
        }

        .sidebar-link svg {
            width: 1rem;
            height: 1rem;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            transition: stroke 0.16s;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.82);
        }

        .sidebar-link.active {
            background: rgba(255,255,255,0.09);
            color: #fff;
            font-weight: 500;
        }

        /* Active indicator pip */
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 2.5px;
            height: 55%;
            border-radius: 2px;
            background: linear-gradient(180deg, var(--terra), var(--gold));
        }

        /* ── Collapsible group ── */
        .sidebar-group { margin-bottom: 0.1rem; }

        .sidebar-group-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.52rem 0.75rem;
            border-radius: 7px;
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.02em;
            color: rgba(255, 255, 255, 0.9);
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.16s, color 0.16s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-align: left;
        }

        .sidebar-group-toggle:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.82);
        }

        .sidebar-group-toggle.active {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.82);
        }

        .sidebar-group-toggle-inner {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .sidebar-group-toggle svg.icon {
            width: 1rem;
            height: 1rem;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .sidebar-group-toggle svg.chevron {
            width: 0.75rem;
            height: 0.75rem;
            stroke: rgba(255,255,255,0.25);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            transition: transform 0.22s ease;
        }

        /* Children panel */
        .sidebar-group-children {
            display: none;
            padding: 0.2rem 0 0.2rem 0.75rem;
            margin-left: 1.65rem;
            border-left: 1px solid rgba(255,255,255,0.07);
            margin-top: 0.1rem;
        }

        .sidebar-group-children.open { display: block; }

        details[open] .sidebar-group-toggle svg.chevron { transform: rotate(180deg); }

        .sidebar-group-children .sidebar-link {
            font-size: 0.76rem;
            color: rgba(255,255,255,0.38);
            padding: 0.42rem 0.65rem;
        }

        .sidebar-group-children .sidebar-link.active { color: #fff; }

        /* ── Footer: user ── */
        .sidebar-footer {
            padding: 0.85rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.6rem;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-user-avatar {
            width: 1.85rem;
            height: 1.85rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--terra) 0%, var(--gold) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user-info { flex: 1; overflow: hidden; }

        .sidebar-user-name {
            font-size: 0.78rem;
            font-weight: 500;
            color: rgba(255,255,255,0.75);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .sidebar-user-role {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.28);
            font-weight: 300;
            letter-spacing: 0.04em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-logout {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.25);
            transition: color 0.15s, background 0.15s;
            flex-shrink: 0;
        }

        .sidebar-user-logout:hover {
            color: var(--terra-lt);
            background: rgba(196,120,74,0.12);
        }

        .sidebar-user-logout svg {
            width: 0.85rem;
            height: 0.85rem;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.75;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ══ MAIN CONTENT ═════════════════════════════════════ */
        .layout-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .layout-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .layout-header {
            background: #fff;
            border-bottom: 1px solid var(--linen);
            padding: 1.1rem 2rem;
        }

        .layout-content { flex: 1; }

        /* ══ MOBILE ═══════════════════════════════════════════ */
        .sidebar-mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 49;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.25);
            }

            .sidebar-mobile-overlay.open { display: block; }

            .layout-main { margin-left: 0; }

            .layout-header { padding: 0.85rem 1.25rem; }
        }
    </style>
</head>
<body>

{{-- Mobile overlay --}}
<div class="sidebar-mobile-overlay" id="sb-overlay" onclick="closeSidebar()"></div>

<div class="layout-root">

    {{-- ════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-fallback">
                <svg viewBox="0 0 24 24"><path d="M3 7h18M3 7a2 2 0 00-2 2v8a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2"/><circle cx="12" cy="13" r="2"/></svg>
            </div>
            <span class="sidebar-brand-name">
                {{ config('app.name', 'Abot Imperial') }}
            </span>
        </a>
        
        <!-- Mobile close button -->
        <button class="lg:hidden absolute top-4 right-4 p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10" onclick="closeSidebar()" aria-label="Close menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Nav --}}
        <nav class="sidebar-nav">

            {{-- Main --}}
            <span class="sidebar-section-label">Main</span>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>

            <a href="{{ route('pos.index') }}"
               class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/><path d="M8 13h8M8 17h5"/></svg>
                Point of Sale
            </a>

            {{-- Hospitality --}}
            <span class="sidebar-section-label">Hospitality</span>

            <a href="{{ route('bookings.index') }}"
               class="sidebar-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 7h18M3 7a2 2 0 00-2 2v8a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2"/><circle cx="12" cy="13" r="2"/></svg>
                Active Stays
            </a>

            <a href="{{ route('rooms.index') }}"
               class="sidebar-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 21V5a2 2 0 012-2h9.5a2 2 0 012 2v16M4 21h16M10 21v-6h4v6"/></svg>
                Rooms
            </a>

            @if(Route::has('room-types.index'))
                @can('room-types.view')
                <a href="{{ route('room-types.index') }}"
                   class="sidebar-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Room Types
                </a>
                @endcan
                @endif

            <a href="{{ route('restaurant.index') }}"
               class="sidebar-link {{ request()->routeIs('restaurant.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h2a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 00-5 5v6h3l-1 11"/></svg>
                Restaurant
            </a>

            {{-- Operations --}}
            <span class="sidebar-section-label">Operations</span>

            {{-- Inventory group --}}
            <details class="sidebar-group" @if(request()->is('inventory*')) open @endif>
                <summary class="sidebar-group-toggle {{ request()->is('inventory*') ? 'active' : '' }}" style="list-style:none">
                    <span class="sidebar-group-toggle-inner">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4M3 7v6m18-6v6"/></svg>
                        Inventory
                    </span>
                    <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div class="sidebar-group-children {{ request()->is('inventory*') ? 'open' : '' }}">
                    <a href="{{ route('inventory.categories.index') }}"
                       class="sidebar-link {{ request()->routeIs('inventory.categories.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Categories
                    </a>
                    <a href="{{ route('inventory.products.index') }}"
                       class="sidebar-link {{ request()->routeIs('inventory.products.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M7 7l5-3 5 3v6l-5 3-5-3V7z"/></svg>
                        Products
                    </a>
                    <a href="{{ route('inventory.movements.index') }}"
                       class="sidebar-link {{ request()->routeIs('inventory.movements.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M7 11l5-5 5 5M7 13l5 5 5-5"/></svg>
                        Movements
                    </a>
                    <a href="{{ route('inventory.suppliers.index') }}"
                       class="sidebar-link {{ request()->routeIs('inventory.suppliers.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M16 16h6v-5l-3-4H7v9h3m0 0a2 2 0 104 0m-4 0a2 2 0 014 0"/></svg>
                        Suppliers
                    </a>
                    <a href="{{ route('inventory.purchases.index') }}"
                       class="sidebar-link {{ request()->routeIs('inventory.purchases.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M7 7h10M7 11h10M7 15h7M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Purchases
                    </a>
                    @if(Route::has('kitchen-stock.index'))
                    <a href="{{ route('kitchen-stock.index') }}"
                       class="sidebar-link {{ request()->routeIs('kitchen-stock.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        Kitchen Stock
                    </a>
                    @endif
                    @if(Route::has('recipes.index'))
                    <a href="{{ route('recipes.index') }}"
                       class="sidebar-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Recipes
                    </a>
                    @endif
                    @if(Route::has('kitchen-purchases.index'))
                    <a href="{{ route('kitchen-purchases.index') }}"
                       class="sidebar-link {{ request()->routeIs('kitchen-purchases.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M16 16h6v-5l-3-4H7v9h3m0 0a2 2 0 104 0m-4 0a2 2 0 014 0"/></svg>
                        Kitchen Purchases
                    </a>
                    @endif
                </div>
            </details>

            <a href="{{ route('reports.index') }}"
               class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 19h16M7 16V8m5 8V5m5 11v-6"/></svg>
                Reports
            </a>

            @can('expenses.view')
            <a href="{{ route('expenses.index') }}"
               class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Expenses
            </a>
            @endcan

            {{-- Administration --}}
            @if(auth()->user()->can('manage-users') || auth()->user()->can('manage-roles') || auth()->user()->can('manage-permissions'))
            <span class="sidebar-section-label">Administration</span>

            <details class="sidebar-group" @if(request()->routeIs('admin.*')) open @endif>
                <summary class="sidebar-group-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="list-style:none">
                    <span class="sidebar-group-toggle-inner">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 3l7 4v6c0 4-3 7-7 8-4-1-7-4-7-8V7l7-4z"/></svg>
                        Administration
                    </span>
                    <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div class="sidebar-group-children {{ request()->routeIs('admin.*') ? 'open' : '' }}">
                    @can('manage-users')
                    <a href="{{ route('admin.users.roles.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.users.roles.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        User Roles
                    </a>
                    @endcan
                    @can('manage-roles')
                    <a href="{{ route('admin.roles.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M12 3l7 4v6c0 4-3 7-7 8-4-1-7-4-7-8V7l7-4z"/></svg>
                        Roles
                    </a>
                    @endcan
                    @can('manage-permissions')
                    <a href="{{ route('admin.permissions.matrix') }}"
                       class="sidebar-link {{ request()->routeIs('admin.permissions.matrix*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        Permissions
                    </a>
                    @endcan
                </div>
            </details>
            @endif

            {{-- System --}}
            <span class="sidebar-section-label">System</span>

            <a href="{{ route('settings.index') }}"
               class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                Settings
            </a>

        </nav>

        {{-- Footer: logged-in user --}}
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">{{ Auth::user()->getRoleNames()->first() ?? 'Staff' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-user-logout" title="Sign out">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ════════════════════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════════════════════ --}}
    <div class="layout-main">

        {{-- Top navigation bar --}}
        <div class="layout-topbar">
            @include('layouts.navigation')
        </div>

        {{-- Page header slot --}}
        @isset($header)
            <header class="layout-header">
                {{ $header }}
            </header>
        @endisset

        {{-- Page content --}}
        <main class="layout-content">
            {{ $slot }}
        </main>

    </div>
</div>

<script>
(function () {
    /* ── Sidebar: details/summary chevron sync ── */
    document.querySelectorAll('.sidebar-group').forEach(details => {
        const children = details.querySelector('.sidebar-group-children');
        details.addEventListener('toggle', () => {
            if (children) children.classList.toggle('open', details.open);
        });
    });

    /* ── Mobile sidebar toggle ── */
    window.openSidebar = function () {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sb-overlay').classList.add('open');
    };

    window.closeSidebar = function () {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sb-overlay').classList.remove('open');
    };

    /* ── Auto-close sidebar on mobile when clicking links ── */
    if (window.innerWidth < 1024) {
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });
    }

    /* ── Close sidebar on escape key ── */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
})();
</script>

</body>
</html>