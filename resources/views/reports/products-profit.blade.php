<x-app-layout>
    <x-slot name="header">
        <div class="ps-header">
            <div class="ps-header__eyebrow">Analytics</div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <h2 class="ps-header__title">Product Profit</h2>
                <a href="{{ route('reports.index') }}" class="ps-btn ps-btn--ghost" style="font-family:'DM Sans',sans-serif;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

        :root {
            --ink:         #0f1117;
            --ink-muted:   #6b7280;
            --ink-faint:   #9ca3af;
            --surface:     #ffffff;
            --surface-2:   #f8f9fb;
            --surface-3:   #f0f2f5;
            --border:      #e5e7eb;
            --border-strong: #d1d5db;
            --accent:      #2563eb;
            --accent-light:#dbeafe;
            --green:       #059669;
            --green-light: #d1fae5;
            --red:         #dc2626;
            --red-light:   #fee2e2;
            --orange:      #d97706;
            --orange-light:#fef3c7;
            --radius:      10px;
            --radius-lg:   16px;
            --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --font:        'DM Sans', sans-serif;
            --mono:        'DM Mono', monospace;
        }

        /* ── Layout ─────────────────────────────────────────── */
        .ps-wrap { font-family:var(--font); padding:2rem 2.5rem 3rem; max-width:1200px; color:var(--ink); }

        /* ── Header slot ────────────────────────────────────── */
        .ps-header { display:flex; flex-direction:column; gap:2px; width:100%; }
        .ps-header__eyebrow { font-family:var(--mono); font-size:.7rem; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); }
        .ps-header__title { font-size:1.35rem; font-weight:600; color:var(--ink); letter-spacing:-.02em; }

        /* ── Buttons ────────────────────────────────────────── */
        .ps-btn {
            display:inline-flex; align-items:center; gap:.4rem;
            font-family:var(--font); font-size:.875rem; font-weight:500;
            height:38px; padding:0 1.1rem; border-radius:var(--radius);
            cursor:pointer; transition:background .15s, transform .1s, box-shadow .15s;
            text-decoration:none; border:none;
        }
        .ps-btn:active { transform:translateY(1px); }
        .ps-btn--primary { background:var(--ink); color:#fff; }
        .ps-btn--primary:hover { background:#1f2937; box-shadow:var(--shadow-sm); }
        .ps-btn--ghost { background:var(--surface); color:var(--ink); border:1px solid var(--border-strong); }
        .ps-btn--ghost:hover { background:var(--surface-3); }

        /* ── Filter bar ─────────────────────────────────────── */
        .ps-filters {
            display:flex; align-items:flex-end; gap:1rem; flex-wrap:wrap;
            background:var(--surface); border:1px solid var(--border);
            border-radius:var(--radius-lg); padding:1.25rem 1.5rem;
            box-shadow:var(--shadow-sm);
        }
        .ps-field { display:flex; flex-direction:column; gap:5px; }
        .ps-field label { font-size:.72rem; font-weight:500; letter-spacing:.06em; text-transform:uppercase; color:var(--ink-muted); }
        .ps-field input[type="number"],
        .ps-field input[type="date"] {
            font-family:var(--font); font-size:.9rem; color:var(--ink);
            background:var(--surface-2); border:1px solid var(--border-strong);
            border-radius:var(--radius); padding:.5rem .75rem; outline:none;
            transition:border-color .15s, box-shadow .15s;
        }
        .ps-field input[type="number"] { width:88px; }
        .ps-field input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-light); background:var(--surface); }
        .ps-filter-divider { width:1px; height:36px; background:var(--border); align-self:flex-end; margin-bottom:2px; }
        .ps-filter-actions { display:flex; gap:.6rem; align-items:flex-end; }

        /* ── Stat cards ─────────────────────────────────────── */
        .ps-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
        @media(max-width:768px){ .ps-stats { grid-template-columns:1fr; } }

        .ps-stat {
            background:var(--surface); border:1px solid var(--border);
            border-radius:var(--radius-lg); padding:1.4rem 1.5rem;
            box-shadow:var(--shadow-sm); display:flex; flex-direction:column; gap:.75rem;
        }
        .ps-stat__top { display:flex; align-items:flex-start; justify-content:space-between; }
        .ps-stat__label { font-size:.72rem; font-weight:600; letter-spacing:.07em; text-transform:uppercase; color:var(--ink-faint); margin-bottom:.3rem; }
        .ps-stat__value { font-size:1.65rem; font-weight:600; letter-spacing:-.03em; font-family:var(--mono); line-height:1; }
        .ps-stat__icon {
            width:40px; height:40px; border-radius:10px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .ps-stat__bar { height:3px; border-radius:99px; background:var(--surface-3); overflow:hidden; }
        .ps-stat__bar-fill { height:100%; border-radius:99px; }

        .ps-stat--blue   .ps-stat__value { color:var(--accent); }
        .ps-stat--blue   .ps-stat__icon  { background:var(--accent-light); }
        .ps-stat--blue   .ps-stat__bar-fill { background:var(--accent); width:75%; }

        .ps-stat--orange .ps-stat__value { color:var(--orange); }
        .ps-stat--orange .ps-stat__icon  { background:var(--orange-light); }
        .ps-stat--orange .ps-stat__bar-fill { background:var(--orange); width:55%; }

        .ps-stat--profit .ps-stat__icon  { background:var(--green-light); }
        .ps-stat--profit .ps-stat__value.positive { color:var(--green); }
        .ps-stat--profit .ps-stat__value.negative { color:var(--red); }
        .ps-stat--profit.is-negative .ps-stat__icon { background:var(--red-light); }
        .ps-stat--profit .ps-stat__bar-fill.positive { background:var(--green); width:40%; }
        .ps-stat--profit .ps-stat__bar-fill.negative { background:var(--red); width:40%; }

        /* ── Generic card ───────────────────────────────────── */
        .ps-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); box-shadow:var(--shadow-sm); overflow:hidden; }
        .ps-card__head {
            display:flex; align-items:center; justify-content:space-between;
            padding:1.1rem 1.5rem .9rem; border-bottom:1px solid var(--border);
        }
        .ps-card__title { font-size:.95rem; font-weight:600; letter-spacing:-.01em; }
        .ps-card__legend { display:flex; gap:1rem; }
        .ps-legend-item { display:flex; align-items:center; gap:.4rem; font-size:.78rem; color:var(--ink-muted); font-weight:500; }
        .ps-legend-dot { width:8px; height:8px; border-radius:50%; }
        .ps-card__body { padding:1.25rem 1.5rem; }

        /* ── Table ──────────────────────────────────────────── */
        .ps-table-wrap { overflow-x:auto; }
        .ps-table { width:100%; border-collapse:collapse; font-size:.875rem; }
        .ps-table thead th {
            padding:.65rem 1.25rem; text-align:left;
            font-size:.7rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase;
            color:var(--ink-faint); background:var(--surface-2); border-bottom:1px solid var(--border); white-space:nowrap;
        }
        .ps-table tbody tr { transition:background .1s; }
        .ps-table tbody tr:hover { background:var(--surface-2); }
        .ps-table tbody td { padding:.85rem 1.25rem; border-bottom:1px solid var(--border); color:var(--ink); vertical-align:middle; }
        .ps-table tbody tr:last-child td { border-bottom:none; }
        .col-name   { font-weight:500; }
        .col-num    { font-family:var(--mono); font-size:.85rem; color:var(--ink-muted); }
        .col-rev    { font-family:var(--mono); font-size:.85rem; font-weight:500; color:var(--accent); }
        .col-cost   { font-family:var(--mono); font-size:.85rem; color:var(--orange); }
        .col-profit-pos { font-family:var(--mono); font-size:.85rem; font-weight:500; color:var(--green); }
        .col-profit-neg { font-family:var(--mono); font-size:.85rem; font-weight:500; color:var(--red); }

        .ps-rank { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:6px; background:var(--surface-3); font-size:.7rem; font-weight:600; color:var(--ink-muted); margin-right:.5rem; font-family:var(--mono); }
        .ps-rank--top { background:var(--accent-light); color:var(--accent); }

        .ps-empty { padding:3rem 1.5rem; text-align:center; color:var(--ink-faint); font-size:.875rem; }

        /* ── Stack spacing ──────────────────────────────────── */
        .ps-stack { display:flex; flex-direction:column; gap:1.5rem; }

        @media(max-width:640px){
            .ps-wrap { padding:1.25rem 1rem 2rem; }
            .ps-filters { flex-direction:column; align-items:stretch; }
            .ps-filter-divider { display:none; }
            .ps-filter-actions { flex-direction:column; }
            .ps-btn { width:100%; justify-content:center; }
        }
    </style>

    <div class="ps-wrap">
        <div class="ps-stack">

            {{-- ── Filter bar ── --}}
            <form method="GET" class="ps-filters">
                <div class="ps-field">
                    <label for="pp-days">Days</label>
                    <input id="pp-days" type="number" min="1" max="180" name="days" value="{{ $days }}" placeholder="30" />
                </div>

                <div class="ps-filter-divider"></div>

                <div class="ps-field">
                    <label for="pp-from">From</label>
                    <input id="pp-from" type="date" name="from" value="{{ optional($from)->toDateString() }}" />
                </div>

                <div class="ps-field">
                    <label for="pp-to">To</label>
                    <input id="pp-to" type="date" name="to" value="{{ optional($to)->toDateString() }}" />
                </div>

                <div class="ps-filter-divider"></div>

                <div class="ps-filter-actions">
                    <button type="submit" class="ps-btn ps-btn--primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 10 4 15 9 20"/><path d="M20 4v7a4 4 0 0 1-4 4H4"/></svg>
                        Apply
                    </button>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="ps-btn ps-btn--ghost">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                </div>
            </form>

            {{-- ── Stat cards ── --}}
            <div class="ps-stats">
                {{-- Revenue --}}
                <div class="ps-stat ps-stat--blue">
                    <div class="ps-stat__top">
                        <div>
                            <div class="ps-stat__label">Total Revenue</div>
                            <div class="ps-stat__value"><x-currency :amount="$totalRevenue" /></div>
                        </div>
                        <div class="ps-stat__icon">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="ps-stat__bar"><div class="ps-stat__bar-fill"></div></div>
                </div>

                {{-- Cost --}}
                <div class="ps-stat ps-stat--orange">
                    <div class="ps-stat__top">
                        <div>
                            <div class="ps-stat__label">Total Cost</div>
                            <div class="ps-stat__value"><x-currency :amount="$totalCost" /></div>
                        </div>
                        <div class="ps-stat__icon">
                            <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <div class="ps-stat__bar"><div class="ps-stat__bar-fill"></div></div>
                </div>

                {{-- Profit --}}
                <div class="ps-stat ps-stat--profit {{ $totalProfit < 0 ? 'is-negative' : '' }}">
                    <div class="ps-stat__top">
                        <div>
                            <div class="ps-stat__label">Net Profit</div>
                            <div class="ps-stat__value {{ $totalProfit >= 0 ? 'positive' : 'negative' }}">
                                <x-currency :amount="$totalProfit" />
                            </div>
                        </div>
                        <div class="ps-stat__icon">
                            <svg width="18" height="18" fill="none" stroke="{{ $totalProfit >= 0 ? '#059669' : '#dc2626' }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <div class="ps-stat__bar">
                        <div class="ps-stat__bar-fill {{ $totalProfit >= 0 ? 'positive' : 'negative' }}"></div>
                    </div>
                </div>
            </div>

            {{-- ── Chart ── --}}
            <div class="ps-card">
                <div class="ps-card__head">
                    <span class="ps-card__title">Profit Over Time</span>
                    <div class="ps-card__legend">
                        <span class="ps-legend-item"><span class="ps-legend-dot" style="background:#059669"></span>Revenue</span>
                        <span class="ps-legend-item"><span class="ps-legend-dot" style="background:#d97706"></span>Cost</span>
                        <span class="ps-legend-item"><span class="ps-legend-dot" style="background:#2563eb"></span>Profit</span>
                    </div>
                </div>
                <div class="ps-card__body">
                    <canvas id="productProfitChart" height="75"></canvas>
                </div>
            </div>

            {{-- ── Table ── --}}
            <div class="ps-card">
                <div class="ps-card__head">
                    <span class="ps-card__title">Per-Product Profit</span>
                    <span style="font-size:.78rem;color:var(--ink-faint);font-family:var(--mono)">
                        {{ $details->count() }} product{{ $details->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="ps-table-wrap">
                    <table class="ps-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty Sold</th>
                                <th>Revenue</th>
                                <th>Cost</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($details as $i => $row)
                                <tr>
                                    <td>
                                        <span class="ps-rank {{ $i < 3 ? 'ps-rank--top' : '' }}">{{ $i + 1 }}</span>
                                    </td>
                                    <td class="col-name">{{ $row->name }}</td>
                                    <td class="col-num">{{ number_format((int) $row->total_qty) }}</td>
                                    <td class="col-rev"><x-currency :amount="$row->total_revenue" /></td>
                                    <td class="col-cost"><x-currency :amount="$row->total_cost" /></td>
                                    <td class="{{ $row->total_profit >= 0 ? 'col-profit-pos' : 'col-profit-neg' }}">
                                        <x-currency :amount="$row->total_profit" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="ps-empty">No profit data for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- /.ps-stack --}}
    </div>{{-- /.ps-wrap --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const series  = @json($series);
        const labels  = series.map(s => s.date);
        const revenue = series.map(s => s.revenue);
        const cost    = series.map(s => s.cost);
        const profit  = series.map(s => s.profit);

        new Chart(document.getElementById('productProfitChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: revenue,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,.07)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#059669',
                        tension: .35,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Cost',
                        data: cost,
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217,119,6,.07)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#d97706',
                        tension: .35,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Profit',
                        data: profit,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,.07)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb',
                        tension: .35,
                        fill: true,
                        yAxisID: 'y',
                    },
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f1117',
                        titleColor: '#9ca3af',
                        bodyColor: '#f9fafb',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { family: 'DM Mono', size: 11 },
                        bodyFont:  { family: 'DM Sans',  size: 13 },
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0,0,0,.04)' },
                        ticks: { color: '#9ca3af', font: { family: 'DM Mono', size: 11 }, maxTicksLimit: 10 },
                        border: { color: '#e5e7eb' },
                    },
                    y: {
                        type: 'linear',
                        position: 'left',
                        grid: { color: 'rgba(0,0,0,.04)' },
                        ticks: { color: '#6b7280', font: { family: 'DM Mono', size: 11 } },
                        border: { dash: [4,4], color: 'transparent' },
                    },
                }
            }
        });
    </script>
</x-app-layout>