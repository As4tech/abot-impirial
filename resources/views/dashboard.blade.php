<x-app-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap');

    :root {
        --forest:    #1a2e1e;
        --moss:      #2e4a33;
        --sage:      #5a7a5e;
        --terra:     #c4784a;
        --terra-lt:  #d9956d;
        --ivory:     #f8f4ee;
        --cream:     #f2ece2;
        --linen:     #e8dfd0;
        --ink:       #1e1a17;
        --ink-md:    #5a5248;
        --ink-lt:    #9a9088;
        --border:    #ddd5c8;
        --gold:      #b89a60;
        --gold-lt:   #d4b87a;
        --blue:      #3b7dd8;
        --blue-lt:   #dbeafe;
        --emerald:   #10b981;
        --emerald-lt:#d1fae5;
        --amber:     #d97706;
        --amber-lt:  #fef3c7;
        --rose:      #e05252;
        --rose-lt:   #fee2e2;
        --card-shadow: 0 1px 3px rgba(30,26,23,0.07), 0 4px 16px rgba(30,26,23,0.05);
        --card-shadow-hover: 0 2px 8px rgba(30,26,23,0.1), 0 8px 24px rgba(30,26,23,0.08);
    }

    .db-wrap {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--cream);
        min-height: 100vh;
        color: var(--ink);
    }

    /* ══ PAGE HEADER ══════════════════════════════════════ */
    .db-header {
        background: var(--forest);
        padding: 1.5rem 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .db-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 50% 80% at 90% 50%, rgba(184,154,96,0.12) 0%, transparent 60%),
            radial-gradient(ellipse 30% 60% at 10% 100%, rgba(196,120,74,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    /* ── Header top row ── */
    .db-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 1;
        flex-wrap: wrap;
    }

    .db-title-group { flex-shrink: 0; }

    .db-eyebrow {
        font-size: 0.62rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--terra-lt);
        margin-bottom: 0.3rem;
        font-weight: 400;
    }

    .db-title {
        font-family: 'Plus Jakarta Sans', serif;
        font-size: 1.85rem;
        font-weight: 400;
        color: #fff;
        line-height: 1.1;
    }

    .db-title em { font-style: italic; color: var(--gold-lt); }

    /* ══ FILTER BAR ════════════════════════════════════════ */
    .db-filter-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Quick-range pill group */
    .db-filter-pills {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.2rem;
        gap: 0.1rem;
    }

    .db-filter-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.85rem;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 400;
        letter-spacing: 0.04em;
        color: rgba(255,255,255,0.48);
        text-decoration: none;
        transition: background 0.18s, color 0.18s;
        white-space: nowrap;
        cursor: pointer;
        font-family: 'Jost', sans-serif;
    }

    .db-filter-pill:hover {
        color: rgba(255,255,255,0.85);
        background: rgba(255,255,255,0.07);
    }

    .db-filter-pill.active {
        background: rgba(255,255,255,0.13);
        color: #fff;
        font-weight: 500;
    }

    /* Thin vertical sep */
    .db-filter-sep {
        width: 1px;
        height: 1.5rem;
        background: rgba(255,255,255,0.12);
        flex-shrink: 0;
    }

    /* Custom date range */
    .db-filter-dates {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.3rem 0.65rem;
    }

    .db-filter-date-label {
        font-size: 0.6rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.28);
        font-weight: 400;
        white-space: nowrap;
        padding-right: 0.1rem;
    }

    .db-date-input {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 5px;
        padding: 0.3rem 0.55rem;
        font-family: 'Jost', sans-serif;
        font-size: 0.72rem;
        font-weight: 300;
        color: rgba(255,255,255,0.65);
        outline: none;
        transition: border-color 0.2s, background 0.2s;
        cursor: pointer;
        color-scheme: dark;
    }

    .db-date-input::-webkit-calendar-picker-indicator {
        opacity: 0.45;
        cursor: pointer;
        filter: invert(0.7);
    }

    .db-date-input:focus,
    .db-date-input:hover {
        border-color: rgba(184,154,96,0.45);
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.88);
    }

    .db-filter-arrow {
        color: rgba(255,255,255,0.2);
        font-size: 0.7rem;
        flex-shrink: 0;
        line-height: 1;
    }

    /* Apply button — terracotta → gold gradient */
    .db-filter-apply {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.95rem;
        border-radius: 7px;
        background: linear-gradient(135deg, var(--terra) 0%, var(--gold) 100%);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(196,120,74,0.35);
    }

    .db-filter-apply svg {
        width: 0.72rem;
        height: 0.72rem;
        stroke: #fff;
        fill: none;
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .db-filter-apply:hover {
        opacity: 0.9;
        box-shadow: 0 3px 12px rgba(196,120,74,0.45);
    }

    .db-filter-apply:active { transform: scale(0.97); }

    /* Active range label badge */
    .db-range-badge {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 0.45rem 0.8rem;
        font-size: 0.72rem;
        color: rgba(255,255,255,0.5);
        font-weight: 300;
        letter-spacing: 0.05em;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .db-range-badge svg {
        width: 0.82rem;
        height: 0.82rem;
        stroke: var(--gold);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }

    /* ══ HEADER KPI CARDS ═════════════════════════════════ */
    .db-header-kpis {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .db-header-kpi {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px 10px 0 0;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.2s;
        position: relative;
        overflow: hidden;
    }

    .db-header-kpi::after {
        content: '';
        position: absolute;
        bottom: 0; left: 1.25rem; right: 1.25rem;
        height: 1px;
        background: linear-gradient(90deg, var(--terra), var(--gold), transparent);
        opacity: 0.5;
    }

    .db-header-kpi:hover { background: rgba(255,255,255,0.09); }

    .db-header-kpi-label {
        font-size: 0.65rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.38);
        font-weight: 400;
        margin-bottom: 0.4rem;
    }

    .db-header-kpi-value {
        font-family: 'Plus Jakarta Sans', serif;
        font-size: 1.9rem;
        font-weight: 500;
        color: #fff;
        line-height: 1;
        letter-spacing: -0.01em;
    }

    .db-header-kpi-icon {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .db-header-kpi-icon svg { width: 1rem; height: 1rem; fill: currentColor; }

    /* ══ BODY ═════════════════════════════════════════════ */
    .db-body {
        padding: 1.75rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ══ STAT CARDS ═══════════════════════════════════════ */
    .db-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .db-stat {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.4rem;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
        border: 1px solid rgba(221,213,200,0.6);
        animation: fadeUp 0.5s ease both;
    }

    .db-stat:nth-child(1) { animation-delay: 0.05s; }
    .db-stat:nth-child(2) { animation-delay: 0.1s; }
    .db-stat:nth-child(3) { animation-delay: 0.15s; }
    .db-stat:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-2px); }

    .db-stat-icon {
        width: 2.5rem; height: 2.5rem;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .db-stat-icon svg { width: 1.1rem; height: 1.1rem; fill: currentColor; }

    .db-stat-label {
        font-size: 0.65rem;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: var(--ink-lt);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .db-stat-value {
        font-family: 'Plus Jakarta Sans', serif;
        font-size: 1.65rem;
        font-weight: 500;
        color: var(--ink);
        line-height: 1;
    }

    /* ══ MAIN GRID ════════════════════════════════════════ */
    .db-main-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.25rem;
    }

    /* ══ CARDS ════════════════════════════════════════════ */
    .db-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(221,213,200,0.6);
        overflow: hidden;
        animation: fadeUp 0.5s ease 0.2s both;
    }

    .db-card-head {
        padding: 1.2rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--linen);
    }

    .db-card-title {
        font-family: 'Plus Jakarta Sans', serif;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--ink);
    }

    .db-card-sub { font-size: 0.72rem; color: var(--ink-lt); font-weight: 300; margin-top: 0.1rem; }
    .db-card-body { padding: 1.5rem; }

    /* ══ REVENUE BREAKDOWN ════════════════════════════════ */
    .db-breakdown-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: center;
    }

    .db-chart-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .db-chart-wrap canvas { max-width: 220px; max-height: 220px; }

    .db-legend-list { display: flex; flex-direction: column; gap: 0.75rem; }

    .db-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.9rem;
        background: var(--ivory);
        border-radius: 9px;
        border: 1px solid var(--linen);
        transition: border-color 0.2s, background 0.2s;
    }

    .db-legend-item:hover { border-color: var(--border); background: var(--cream); }

    .db-legend-dot-label {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.8rem;
        color: var(--ink-md);
        font-weight: 400;
    }

    .db-legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .db-legend-values { text-align: right; }

    .db-legend-amount {
        font-family: 'Plus Jakarta Sans', serif;
        font-size: 1rem;
        font-weight: 500;
        color: var(--ink);
        line-height: 1;
    }

    .db-legend-pct { font-size: 0.68rem; color: var(--ink-lt); margin-top: 0.15rem; }

    /* ══ LOW STOCK TABLE ══════════════════════════════════ */
    .db-stock-card { animation-delay: 0.25s; }
    .db-stock-table { width: 100%; border-collapse: collapse; }

    .db-stock-table thead th {
        font-size: 0.62rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--ink-lt);
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        text-align: left;
        background: var(--ivory);
        border-bottom: 1px solid var(--linen);
    }

    .db-stock-table thead th:last-child { text-align: right; }

    .db-stock-table tbody tr {
        border-bottom: 1px solid var(--linen);
        transition: background 0.15s;
    }

    .db-stock-table tbody tr:last-child { border-bottom: none; }
    .db-stock-table tbody tr:hover { background: var(--ivory); }

    .db-stock-table tbody td {
        padding: 0.75rem 1.25rem;
        font-size: 0.83rem;
        color: var(--ink-md);
        vertical-align: middle;
    }

    .db-stock-table tbody td:last-child { text-align: right; }

    .db-stock-name { font-weight: 500; color: var(--ink); font-size: 0.85rem; }
    .db-stock-unit { font-size: 0.72rem; color: var(--ink-lt); margin-top: 0.1rem; }

    .db-stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .db-stock-badge.critical { background: var(--rose-lt); color: var(--rose); }
    .db-stock-badge.warning  { background: var(--amber-lt); color: var(--amber); }
    .db-stock-badge.ok       { background: var(--emerald-lt); color: var(--emerald); }

    .db-empty {
        padding: 2.5rem 1.5rem;
        text-align: center;
        color: var(--ink-lt);
        font-size: 0.82rem;
        font-weight: 300;
    }

    .db-empty svg {
        width: 2rem; height: 2rem;
        stroke: var(--linen); fill: none; stroke-width: 1.5;
        margin: 0 auto 0.75rem; display: block;
    }

    /* ══ Animations ══════════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ══ Responsive ══════════════════════════════════════ */
    @media (max-width: 1024px) {
        .db-main-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 820px) {
        .db-filter-dates, .db-filter-sep { display: none; }
    }

    @media (max-width: 640px) {
        .db-header       { padding: 1.25rem 1.25rem 0; }
        .db-body         { padding: 1.25rem; }
        .db-stats-row    { grid-template-columns: 1fr; }
        .db-header-kpis  { grid-template-columns: 1fr; }
        .db-breakdown-inner { grid-template-columns: 1fr; }
        .db-title        { font-size: 1.5rem; }
        .db-header-top   { flex-direction: column; gap: 1rem; }
        .db-filter-bar   { width: 100%; }
    }
</style>

<div class="db-wrap">

    {{-- ════════════ PAGE HEADER ════════════ --}}
    <div class="db-header">
        <div class="db-header-top">

            {{-- Title --}}
            <div class="db-title-group">
                <div class="db-eyebrow">Good morning</div>
                <div class="db-title">Operations <em>Dashboard</em></div>
            </div>

            {{-- ── FILTER BAR ── --}}
            <form method="GET" action="{{ route('dashboard') }}" class="db-filter-bar">

                {{-- Quick range pills --}}
                <div class="db-filter-pills">
                    <a href="{{ route('dashboard', ['range' => 'today']) }}"
                       class="db-filter-pill {{ ($range ?? 'today') === 'today' ? 'active' : '' }}">
                        Today
                    </a>
                    <a href="{{ route('dashboard', ['range' => 'week']) }}"
                       class="db-filter-pill {{ ($range ?? '') === 'week' ? 'active' : '' }}">
                        This Week
                    </a>
                    <a href="{{ route('dashboard', ['range' => 'month']) }}"
                       class="db-filter-pill {{ ($range ?? '') === 'month' ? 'active' : '' }}">
                        This Month
                    </a>
                </div>

                <div class="db-filter-sep"></div>

                {{-- Custom date range --}}
                <div class="db-filter-dates">
                    <span class="db-filter-date-label">From</span>
                    <input class="db-date-input" type="date" name="from" value="{{ $from ?? '' }}">
                    <span class="db-filter-arrow">→</span>
                    <input class="db-date-input" type="date" name="to" value="{{ $to ?? '' }}">
                </div>

                {{-- Apply --}}
                <button type="submit" class="db-filter-apply">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Apply
                </button>

            </form>

            {{-- Range badge --}}
            <div class="db-range-badge">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $rangeLabel ?? 'Today' }}
            </div>

        </div>

        {{-- Header KPI cards --}}
        <div class="db-header-kpis">
            <div class="db-header-kpi">
                <div>
                    <div class="db-header-kpi-label">Expenses · {{ $rangeLabel ?? 'Today' }}</div>
                    <div class="db-header-kpi-value"><x-currency :amount="$todaysExpenses ?? 0" /></div>
                </div>
                <div class="db-header-kpi-icon" style="background:rgba(224,82,82,0.15)">
                    <svg style="color:#f87171" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 5v5.59l3.71 3.71-1.42 1.42L11 13.41V7h2z"/>
                    </svg>
                </div>
            </div>
            <div class="db-header-kpi">
                <div>
                    <div class="db-header-kpi-label">Profit · {{ $rangeLabel ?? 'Today' }}</div>
                    <div class="db-header-kpi-value"><x-currency :amount="$profitToday ?? 0" /></div>
                </div>
                <div class="db-header-kpi-icon" style="background:rgba(184,154,96,0.2)">
                    <svg style="color:#d4b87a" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3 3 7.5 12 12l9-4.5L12 3zM3 12l9 4.5 9-4.5M3 16.5l9 4.5 9-4.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════ BODY ════════════ --}}
    <div class="db-body">

        {{-- Stat cards --}}
        <div class="db-stats-row">
            <div class="db-stat">
                <div class="db-stat-icon" style="background:var(--blue-lt); color:var(--blue)">
                    <svg viewBox="0 0 24 24"><path d="M3 3h18v2H3zM5 7h14v2H5zM3 11h18v2H3zM5 15h14v2H5zM3 19h18v2H3z"/></svg>
                </div>
                <div>
                    <div class="db-stat-label">Sales · {{ $rangeLabel ?? 'Today' }}</div>
                    <div class="db-stat-value"><x-currency :amount="$todaysSales ?? 0" /></div>
                </div>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon" style="background:var(--emerald-lt); color:var(--emerald)">
                    <svg viewBox="0 0 24 24"><path d="M7 4h10l1 3h3v2h-2l-2 10H7L5 9H3V7h3l1-3zm2.2 5 1.2 8h6.2l1.6-8H9.2z"/></svg>
                </div>
                <div>
                    <div class="db-stat-label">Orders · {{ $rangeLabel ?? 'Today' }}</div>
                    <div class="db-stat-value">{{ (int)($todaysOrders ?? 0) }}</div>
                </div>
            </div>

            <div class="db-stat">
                <div class="db-stat-icon" style="background:var(--amber-lt); color:var(--amber)">
                    <svg viewBox="0 0 24 24"><path d="M12 3 3 7.5 12 12l9-4.5L12 3zm0 9 9 4.5L12 21l-9-4.5L12 12z"/></svg>
                </div>
                <div>
                    <div class="db-stat-label">Avg Order Value · {{ $rangeLabel ?? 'Today' }}</div>
                    <div class="db-stat-value"><x-currency :amount="$avgOrder ?? 0" /></div>
                </div>
            </div>
        </div>

        {{-- Main 2-col grid --}}
        <div class="db-main-grid">

            {{-- Revenue Breakdown --}}
            <div class="db-card">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">Revenue Breakdown</div>
                        <div class="db-card-sub">Restaurant · Room Service · Products — {{ $rangeLabel ?? 'Today' }}</div>
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:center">
                        <span style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.65rem;color:var(--ink-lt);letter-spacing:0.08em;text-transform:uppercase;font-weight:400"><span style="width:6px;height:6px;border-radius:50%;background:#3b7dd8;display:inline-block"></span>Rest.</span>
                        <span style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.65rem;color:var(--ink-lt);letter-spacing:0.08em;text-transform:uppercase;font-weight:400"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block"></span>Room</span>
                        <span style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.65rem;color:var(--ink-lt);letter-spacing:0.08em;text-transform:uppercase;font-weight:400"><span style="width:6px;height:6px;border-radius:50%;background:#d97706;display:inline-block"></span>Prod.</span>
                    </div>
                </div>
                <div class="db-card-body">
                    <div class="db-breakdown-inner">
                        <div class="db-chart-wrap">
                            <canvas id="breakdownChart"></canvas>
                        </div>
                        <div class="db-legend-list">
                            <div class="db-legend-item">
                                <div class="db-legend-dot-label"><span class="db-legend-dot" style="background:#3b7dd8"></span>Restaurant</div>
                                <div class="db-legend-values">
                                    <div class="db-legend-amount"><x-currency :amount="$breakdown['restaurant'] ?? 0" /></div>
                                    <div class="db-legend-pct">{{ number_format((($breakdown['restaurant'] ?? 0)/($breakdownTotal ?: 1))*100, 0) }}% of total</div>
                                </div>
                            </div>
                            <div class="db-legend-item">
                                <div class="db-legend-dot-label"><span class="db-legend-dot" style="background:#10b981"></span>Room Service</div>
                                <div class="db-legend-values">
                                    <div class="db-legend-amount"><x-currency :amount="$breakdown['room'] ?? 0" /></div>
                                    <div class="db-legend-pct">{{ number_format((($breakdown['room'] ?? 0)/($breakdownTotal ?: 1))*100, 0) }}% of total</div>
                                </div>
                            </div>
                            <div class="db-legend-item">
                                <div class="db-legend-dot-label"><span class="db-legend-dot" style="background:#d97706"></span>Products</div>
                                <div class="db-legend-values">
                                    <div class="db-legend-amount"><x-currency :amount="$breakdown['products'] ?? 0" /></div>
                                    <div class="db-legend-pct">{{ number_format((($breakdown['products'] ?? 0)/($breakdownTotal ?: 1))*100, 0) }}% of total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alerts --}}
            <div class="db-card db-stock-card">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">Low Stock Alerts</div>
                        <div class="db-card-sub">Items at or below reorder threshold</div>
                    </div>
                    @php $lowCount = count($lowStock ?? []); @endphp
                    @if($lowCount > 0)
                        <span style="background:var(--rose-lt);color:var(--rose);font-size:0.7rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:20px;">
                            {{ $lowCount }} item{{ $lowCount > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                <table class="db-stock-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align:right">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStock ?? [] as $p)
                            @php
                                $qty = (float)($p->stock_quantity ?? 0);
                                $threshold = (int) config('inventory.threshold', 5);
                                $badgeClass = $qty <= 0 ? 'critical' : ($qty <= $threshold ? 'warning' : 'ok');
                            @endphp
                            <tr>
                                <td>
                                    <div class="db-stock-name">{{ $p->name }}</div>
                                    <div class="db-stock-unit">{{ $p->unit }}</div>
                                </td>
                                <td>
                                    <span class="db-stock-badge {{ $badgeClass }}">{{ number_format($p->stock_quantity, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="db-empty">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
                                        All stock levels are healthy.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('breakdownChart');
    if (!ctx) return;

    const vals  = [{{ (float)($breakdown['restaurant'] ?? 0) }}, {{ (float)($breakdown['room'] ?? 0) }}, {{ (float)($breakdown['products'] ?? 0) }}];
    const total = {{ (float)($breakdownTotal ?? 0) }};
    const currency = '{{ function_exists("setting") ? setting("pos.currency","PHP") : "PHP" }}';

    const centerPlugin = {
        id: 'centerText',
        afterDatasetsDraw(chart) {
            const { ctx: c } = chart;
            const pt = chart.getDatasetMeta(0).data[0];
            if (!pt) return;
            c.save();
            c.textAlign = 'center'; c.textBaseline = 'middle';
            c.font = '400 10px Jost, sans-serif'; c.fillStyle = '#9a9088';
            c.fillText('TOTAL', pt.x, pt.y - 13);
            c.font = "500 15px 'Cormorant Garamond', serif"; c.fillStyle = '#1e1a17';
            c.fillText(currency + ' ' + new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total), pt.x, pt.y + 4);
            c.restore();
        }
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Restaurant', 'Room', 'Products'],
            datasets: [{
                data: vals,
                backgroundColor: ['#3b7dd8', '#10b981', '#d97706'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverBorderWidth: 3,
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (i) => ` ${currency} ${new Intl.NumberFormat('en-PH',{minimumFractionDigits:2}).format(i.raw)}` } }
            },
            layout: { padding: 8 },
            animation: { animateRotate: true, duration: 700, easing: 'easeInOutQuart' }
        },
        plugins: [centerPlugin]
    });
})();
</script>
</x-app-layout>