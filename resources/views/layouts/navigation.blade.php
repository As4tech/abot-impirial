<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Jost:wght@300;400;500&display=swap');

    :root {
        --forest:   #1a2e1e;
        --moss:     #2e4a33;
        --sage:     #5a7a5e;
        --terra:    #c4784a;
        --terra-lt: #d9956d;
        --ivory:    #f8f4ee;
        --cream:    #f2ece2;
        --linen:    #e8dfd0;
        --ink:      #1e1a17;
        --ink-md:   #5a5248;
        --ink-lt:   #9a9088;
        --border:   #ddd5c8;
        --gold:     #b89a60;
        --gold-lt:  #d4b87a;
    }

    .nav-root {
        background: var(--forest);
        font-family: 'Jost', sans-serif;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 0 rgba(255,255,255,0.05), 0 4px 24px rgba(0,0,0,0.18);
    }

    /* Thin gold accent line at very top */
    .nav-root::before {
        content: '';
        display: block;
        height: 2px;
        background: linear-gradient(90deg, var(--terra) 0%, var(--gold) 40%, transparent 100%);
    }

    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 58px;
    }

    /* ── Left: Logo + Nav links ── */
    .nav-left {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        text-decoration: none;
        margin-right: 2.5rem;
        flex-shrink: 0;
    }

    .nav-brand img {
        height: 1.6rem;
        width: auto;
        filter: brightness(0) invert(1);
        opacity: 0.85;
    }

    .nav-brand-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 400;
        color: rgba(255,255,255,0.85);
        letter-spacing: 0.1em;
        white-space: nowrap;
    }

    /* Divider between brand and links */
    .nav-brand-sep {
        width: 1px;
        height: 1.25rem;
        background: rgba(255,255,255,0.12);
        margin-right: 2.5rem;
        flex-shrink: 0;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 400;
        letter-spacing: 0.05em;
        color: rgba(255,255,255,0.5);
        text-decoration: none;
        transition: color 0.2s, background 0.2s;
        position: relative;
        white-space: nowrap;
    }

    .nav-link svg {
        width: 0.85rem;
        height: 0.85rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }

    .nav-link:hover {
        color: rgba(255,255,255,0.85);
        background: rgba(255,255,255,0.06);
    }

    .nav-link.active {
        color: #fff;
        background: rgba(255,255,255,0.09);
    }

    /* Active underline pip */
    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0.85rem;
        right: 0.85rem;
        height: 1.5px;
        border-radius: 2px;
        background: linear-gradient(90deg, var(--terra), var(--gold));
    }

    /* ── Right: User area ── */
    .nav-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Notification bell */
    .nav-icon-btn {
        width: 2rem;
        height: 2rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.45);
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color 0.2s, background 0.2s;
        position: relative;
    }

    .nav-icon-btn:hover {
        color: rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.07);
    }

    .nav-icon-btn svg {
        width: 1rem;
        height: 1rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Vertical divider */
    .nav-sep {
        width: 1px;
        height: 1.25rem;
        background: rgba(255,255,255,0.1);
    }

    /* User dropdown trigger */
    .nav-user {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.35rem 0.75rem 0.35rem 0.4rem;
        border-radius: 8px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.09);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-size: 0.78rem;
        font-weight: 400;
        color: rgba(255,255,255,0.75);
        font-family: 'Jost', sans-serif;
        outline: none;
        position: relative;
    }

    .nav-user:hover, .nav-user:focus {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.15);
        color: #fff;
    }

    /* Avatar circle */
    .nav-avatar {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--terra) 0%, var(--gold) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 0.85rem;
        font-weight: 500;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: 0;
    }

    .nav-user-name {
        max-width: 9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .nav-chevron {
        width: 0.75rem;
        height: 0.75rem;
        stroke: rgba(255,255,255,0.4);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: transform 0.2s;
        flex-shrink: 0;
    }

    /* ── Dropdown panel ── */
    .nav-dropdown {
        position: absolute;
        top: calc(100% + 0.6rem);
        right: 0;
        width: 220px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 8px 32px rgba(30,26,23,0.14), 0 2px 8px rgba(30,26,23,0.08);
        overflow: hidden;
        opacity: 0;
        transform: translateY(-6px) scale(0.97);
        transform-origin: top right;
        transition: opacity 0.18s ease, transform 0.18s ease;
        pointer-events: none;
        z-index: 200;
    }

    .nav-dropdown.open {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .nav-dropdown-head {
        padding: 1rem 1.1rem 0.85rem;
        border-bottom: 1px solid var(--linen);
    }

    .nav-dropdown-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem;
        font-weight: 500;
        color: var(--ink);
        line-height: 1.2;
    }

    .nav-dropdown-email {
        font-size: 0.72rem;
        color: var(--ink-lt);
        font-weight: 300;
        margin-top: 0.15rem;
    }

    .nav-dropdown-body {
        padding: 0.4rem;
    }

    .nav-dd-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.7rem;
        border-radius: 7px;
        font-size: 0.82rem;
        color: var(--ink-md);
        text-decoration: none;
        font-weight: 400;
        transition: background 0.15s, color 0.15s;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'font-sans', sans-serif;
        text-align: left;
    }

    .nav-dd-link:hover {
        background: var(--ivory);
        color: var(--ink);
    }

    .nav-dd-link svg {
        width: 0.9rem;
        height: 0.9rem;
        stroke: var(--ink-lt);
        fill: none;
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }

    .nav-dd-link:hover svg { stroke: var(--terra); }

    .nav-dropdown-footer {
        padding: 0.4rem;
        border-top: 1px solid var(--linen);
    }

    .nav-dd-link.logout { color: #e05252; }
    .nav-dd-link.logout svg { stroke: #e05252; }
    .nav-dd-link.logout:hover { background: #fee2e2; color: #c53030; }
    .nav-dd-link.logout:hover svg { stroke: #c53030; }

    /* ── Mobile hamburger ── */
    .nav-hamburger {
        display: none;
        width: 2rem;
        height: 2rem;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.09);
        color: rgba(255,255,255,0.7);
        cursor: pointer;
    }

    .nav-hamburger svg {
        width: 1.1rem;
        height: 1.1rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* ── Mobile drawer ── */
    .nav-mobile {
        display: none;
        border-top: 1px solid rgba(255,255,255,0.07);
        background: var(--forest);
    }

    .nav-mobile.open { display: block; }

    .nav-mobile-links {
        padding: 0.75rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .nav-mobile-user {
        padding: 0.85rem 1.25rem;
        border-top: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .nav-mobile-user-info .name {
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255,255,255,0.8);
    }

    .nav-mobile-user-info .email {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.35);
        font-weight: 300;
    }

    .nav-mobile-actions {
        padding: 0.5rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .nav-mobile-action-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.75rem;
        border-radius: 7px;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.55);
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Jost', sans-serif;
        width: 100%;
        text-align: left;
    }

    .nav-mobile-action-link:hover {
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.85);
    }

    .nav-mobile-action-link svg {
        width: 0.9rem;
        height: 0.9rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .nav-mobile-action-link.logout { color: #f87171; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .nav-links, .nav-brand-sep, .nav-sep, .nav-icon-btn { display: none; }
        .nav-hamburger { display: inline-flex; }
        .nav-user { padding: 0.3rem 0.5rem; }
        .nav-user-name { display: none; }
        .nav-chevron { display: none; }
        .nav-inner { padding: 0 1.25rem; }
    }
</style>

<nav class="nav-root" x-data="{ open: false }">
    <div class="nav-inner">

        {{-- ── Left: Brand + Links ── --}}
        <div class="nav-left">

            <!-- Mobile menu button -->
            <button class="lg:hidden p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 mr-3" onclick="openSidebar()" aria-label="Open menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="nav-brand-sep"></div>

            {{-- Nav links --}}
            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>

                {{-- Extend with additional nav links as needed --}}
                @if(Route::has('reservations.index'))
                <a href="{{ route('reservations.index') }}"
                   class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Reservations
                </a>
                @endif

                @if(Route::has('rooms.index'))
                <a href="{{ route('rooms.index') }}"
                   class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 7h18M3 7a2 2 0 00-2 2v8a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-7"/><circle cx="12" cy="13" r="2"/></svg>
                    Rooms
                </a>
                @endif

                @if(Route::has('room-types.index'))
                @can('room-types.view')
                <a href="{{ route('room-types.index') }}"
                   class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Room Types
                </a>
                @endcan
                @endif

                @if(Route::has('orders.index'))
                @can('orders.view')
                <a href="{{ route('orders.index') }}"
                   class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h2a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 00-5 5v6h3l-1 11"/></svg>
                    Restaurant
                </a>
                @endcan
                @endif

                @if(Route::has('inventory.index'))
                @can('inventory.view')
                <a href="{{ route('inventory.index') }}"
                   class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-7"/><path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/></svg>
                    Inventory
                </a>
                @endcan
                @endif

                @if(Route::has('reports.index'))
                @canany(['reports.view','reports.sales.view','reports.inventory.view','reports.bookings.view','reports.registers.view'])
                <a href="{{ route('reports.index') }}"
                   class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Reports
                </a>
                @endcanany
                @endif
            </div>
        </div>

        {{-- ── Right: Icons + User ── --}}
        <div class="nav-right">

            {{-- Notification bell --}}
            <button class="nav-icon-btn" title="Notifications">
                <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            </button>

            <div class="nav-sep"></div>

            {{-- User dropdown --}}
            <div style="position:relative" id="nav-user-wrap">
                <button class="nav-user" id="nav-user-btn" aria-expanded="false" aria-haspopup="true">
                    <div class="nav-avatar" id="nav-avatar-initials">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="nav-user-name">{{ Auth::user()->name }}</span>
                    <svg class="nav-chevron" id="nav-chevron" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div class="nav-dropdown" id="nav-dropdown" role="menu">
                    <div class="nav-dropdown-head">
                        <div class="nav-dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="nav-dropdown-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="nav-dropdown-body">
                        <a href="{{ route('profile.edit') }}" class="nav-dd-link" role="menuitem">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            My Profile
                        </a>
                        @if(Route::has('settings.index'))
                        <a href="{{ route('settings.index') }}" class="nav-dd-link" role="menuitem">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                            Settings
                        </a>
                        @endif
                    </div>
                    <div class="nav-dropdown-footer">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-dd-link logout" role="menuitem">
                                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile hamburger --}}
            <button class="nav-hamburger" @click="open = !open" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24">
                    <path x-show="!open" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" d="M6 18L18 6M6 6l12 12" style="display:none"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Mobile Drawer ── --}}
    <div class="nav-mobile" :class="{ 'open': open }">
        <div class="nav-mobile-links">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="font-size:0.85rem;padding:0.55rem 0.75rem">
                <svg viewBox="0 0 24 24" style="width:1rem;height:1rem;stroke:currentColor;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            @if(Route::has('reservations.index'))
            <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}" style="font-size:0.85rem;padding:0.55rem 0.75rem">
                <svg viewBox="0 0 24 24" style="width:1rem;height:1rem;stroke:currentColor;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Reservations
            </a>
            @endif
            @if(Route::has('rooms.index'))
            <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" style="font-size:0.85rem;padding:0.55rem 0.75rem">
                <svg viewBox="0 0 24 24" style="width:1rem;height:1rem;stroke:currentColor;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round"><path d="M3 7h18M3 7a2 2 0 00-2 2v8a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-7"/><circle cx="12" cy="13" r="2"/></svg>
                Rooms
            </a>
            @endif
            @if(Route::has('room-types.index'))
            @can('room-types.view')
            <a href="{{ route('room-types.index') }}" class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}" style="font-size:0.85rem;padding:0.55rem 0.75rem">
                <svg viewBox="0 0 24 24" style="width:1rem;height:1rem;stroke:currentColor;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                Room Types
            </a>
            @endcan
            @endif
            @if(Route::has('orders.index'))
            @can('orders.view')
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" style="font-size:0.85rem;padding:0.55rem 0.75rem">
                <svg viewBox="0 0 24 24" style="width:1rem;height:1rem;stroke:currentColor;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round"><path d="M3 2v7c0 1.1.9 2 2 2h2a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 00-5 5v6h3l-1 11"/></svg>
                Restaurant
            </a>
            @endcan
            @endif
        </div>

        {{-- Mobile user info --}}
        <div class="nav-mobile-user">
            <div class="nav-avatar" style="width:2.25rem;height:2.25rem;font-size:1rem">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="nav-mobile-user-info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="nav-mobile-actions">
            <a href="{{ route('profile.edit') }}" class="nav-mobile-action-link">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                My Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-mobile-action-link logout"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
(function () {
    const btn  = document.getElementById('nav-user-btn');
    const dd   = document.getElementById('nav-dropdown');
    const chev = document.getElementById('nav-chevron');
    if (!btn || !dd) return;

    function openDd() {
        dd.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        if (chev) chev.style.transform = 'rotate(180deg)';
    }

    function closeDd() {
        dd.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        if (chev) chev.style.transform = '';
    }

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dd.classList.contains('open') ? closeDd() : openDd();
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('nav-user-wrap').contains(e.target)) closeDd();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDd();
    });
})();
</script>