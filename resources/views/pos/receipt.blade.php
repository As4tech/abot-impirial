@php
    $businessName    = function_exists('setting') ? (setting('general.business_name') ?: config('app.name')) : config('app.name');
    $businessContact = function_exists('setting') ? (setting('general.contact') ?: '') : '';
    $businessAddress = function_exists('setting') ? (setting('general.address') ?: $businessContact) : $businessContact;
    $businessPhone   = function_exists('setting') ? (setting('general.phone') ?: '') : '';
    $currency        = function_exists('setting') ? (setting('pos.currency') ?: 'PHP') : 'PHP';
    $booking    = \App\Models\Booking::where('order_id', $order->id)->first();
    $subTotal   = $order->total_amount;
    $discount   = $order->discount_amount ?? 0;
    $total      = $subTotal - $discount;
    $paidTotal  = $order->payments->where('status','paid')->sum('amount');
    $balRet     = max(0, $paidTotal - $total);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --ink:        #0f1117;
            --ink-mid:    #4a4f5e;
            --ink-light:  #8b91a0;
            --line:       #e4e6ec;
            --surface:    #f7f8fb;
            --white:      #ffffff;
            --accent:     #1a1f36;
            --accent-2:   #2d3561;
            --green:      #0d7a55;
            --green-bg:   #e8f5f0;
            --red:        #c0392b;
            --radius-sm:  6px;
            --radius-md:  10px;
            --radius-lg:  16px;
            --shadow-sm:  0 1px 3px rgba(15,17,23,.06), 0 1px 2px rgba(15,17,23,.04);
            --shadow-md:  0 4px 16px rgba(15,17,23,.08), 0 2px 6px rgba(15,17,23,.05);
            --shadow-lg:  0 8px 32px rgba(15,17,23,.10), 0 4px 12px rgba(15,17,23,.06);
        }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--ink);
            background: #eef0f5;
            min-height: 100vh;
            padding: 32px 16px 64px;
        }

        /* ── Layout ── */
        .page { max-width: 1200px; margin: 0 auto; }

        /* ── Top action bar ── */
        .topbar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .topbar-left { flex: 1; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font: 500 13px/1 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .15s, box-shadow .15s, transform .1s;
            text-decoration: none;
            border: 1.5px solid var(--line);
            background: var(--white);
            color: var(--ink);
            box-shadow: var(--shadow-sm);
        }
        .btn:hover { background: var(--surface); box-shadow: var(--shadow-md); }
        .btn:active { transform: scale(.98); }
        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .btn-primary:hover { background: var(--accent-2); }

        /* ── Flash message ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--green-bg);
            border: 1px solid #a5d6c0;
            color: var(--green);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 500;
        }
        .flash svg { flex-shrink: 0; }

        /* ── Receipt card ── */
        .receipt-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        /* ── Receipt header ── */
        .receipt-header {
            background: var(--accent);
            color: #fff;
            padding: 28px 28px 24px;
            text-align: center;
            position: relative;
        }
        .receipt-header::after {
            content: '';
            display: block;
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 20px;
            background: var(--white);
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 50% 70%, 0 0);
        }
        .biz-name {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -.3px;
            margin-bottom: 4px;
        }
        .biz-sub {
            font-size: 12.5px;
            color: rgba(255,255,255,.65);
            line-height: 1.5;
        }
        .biz-sub + .biz-sub { margin-top: 1px; }

        /* ── Order meta ── */
        .order-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 24px 28px 16px;
            gap: 12px;
        }
        .meta-group {}
        .meta-label {
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: var(--ink-light);
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
            font-family: 'DM Mono', monospace;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
            background: var(--green-bg);
            color: var(--green);
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1.5px dashed var(--line);
            margin: 0 28px;
        }

        /* ── Items table ── */
        .section { padding: 16px 28px; }
        .section-label {
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: var(--ink-light);
            margin-bottom: 12px;
        }

        .items-table { width: 100%; border-collapse: collapse; }
        .items-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--ink-light);
            padding: 0 0 8px;
            border-bottom: 1px solid var(--line);
        }
        .items-table thead th:last-child,
        .items-table thead th:nth-child(3) { text-align: right; }
        .items-table thead th:nth-child(1) { width: 60px; }
        .items-table thead th:nth-child(2) { width: auto; min-width: 200px; }
        .items-table thead th:nth-child(3),
        .items-table thead th:nth-child(4) { width: 120px; }

        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tbody td {
            padding: 10px 0;
            border-bottom: 1px solid var(--line);
            font-size: 13.5px;
            color: var(--ink);
            vertical-align: middle;
        }
        .items-table tbody td:nth-child(2) { 
            word-wrap: break-word; 
            max-width: 300px; 
            line-height: 1.4;
        }
        .items-table tbody td:nth-child(3),
        .items-table tbody td:nth-child(4) { text-align: right; color: var(--ink-mid); }
        .items-table tbody td:nth-child(4) { color: var(--ink); font-weight: 500; }
        .qty-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px; height: 26px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: 'DM Mono', monospace;
            color: var(--ink);
        }
        .item-name {
            font-size: 13.5px;
            font-weight: 500;
        }

        /* ── Totals ── */
        .totals { padding: 12px 28px 20px; }
        .trow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-size: 13.5px;
            color: var(--ink-mid);
        }
        .trow .tval { font-family: 'DM Mono', monospace; font-size: 13px; }
        .trow.discount .tval { color: var(--red); }
        .trow.grand {
            margin-top: 10px;
            padding-top: 14px;
            border-top: 2px solid var(--ink);
            color: var(--ink);
            font-size: 16px;
            font-weight: 600;
        }
        .trow.grand .tval { font-size: 16px; font-weight: 700; }

        /* ── Thank you ── */
        .thankyou {
            text-align: center;
            padding: 20px 28px;
            font-size: 12.5px;
            color: var(--ink-light);
            letter-spacing: .3px;
        }
        .thankyou strong { color: var(--ink-mid); font-weight: 600; }

        /* ── Payments ── */
        .payments-table { width: 100%; border-collapse: collapse; }
        .payments-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--ink-light);
            padding: 0 0 8px;
            border-bottom: 1px solid var(--line);
            text-align: left;
        }
        .payments-table thead th:first-child { width: 100px; }
        .payments-table thead th:last-child { text-align: right; }
        .payments-table tbody td {
            padding: 10px 0;
            font-size: 13px;
            color: var(--ink-mid);
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }
        .payments-table tbody tr:last-child td { border-bottom: none; }
        .payments-table tbody td:first-child {
            font-family: 'DM Mono', monospace;
            font-weight: 500;
            color: var(--ink);
            font-size: 13.5px;
        }
        .payments-table tbody td:last-child { text-align: right; }
        .method-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 500;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--ink-mid);
        }
        .no-payments {
            text-align: center;
            padding: 20px 0;
            color: var(--ink-light);
            font-size: 13px;
        }

        /* ── Footer bar inside card ── */
        .receipt-footer {
            background: var(--surface);
            border-top: 1px solid var(--line);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--ink-light);
        }
        .receipt-footer span { font-family: 'DM Mono', monospace; }

        /* ── Payment panel ── */
        .payment-panel {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 24px 28px;
            margin-top: 20px;
        }
        .panel-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .panel-title svg { color: var(--ink-mid); }

        .field { margin-bottom: 14px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: var(--ink-light);
            margin-bottom: 6px;
        }
        .field input,
        .field select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            font: 14px/1.5 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--white);
            transition: border-color .15s, box-shadow .15s;
            appearance: none;
            -webkit-appearance: none;
        }
        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26,31,54,.08);
        }
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '';
            position: absolute;
            right: 14px; top: 50%; transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid var(--ink-mid);
            pointer-events: none;
        }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .submit-btn {
            width: 100%;
            padding: 12px 20px;
            border: none;
            border-radius: var(--radius-md);
            background: var(--accent);
            color: #fff;
            font: 600 14px/1 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .15s, transform .1s;
            margin-top: 6px;
        }
        .submit-btn:hover { background: var(--accent-2); }
        .submit-btn:active { transform: scale(.99); }

        /* ── Print ── */
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none; border: none; border-radius: 0; }
            .receipt-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

@if(session('status'))
<div class="flash no-print">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('status') }}
</div>
@endif

<div class="page">

    {{-- Action bar --}}
    <div class="topbar no-print">
        <div class="topbar-left">
            <a href="{{ route('pos.index') }}" class="btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to POS
            </a>
        </div>
        <a href="{{ route('pos.receipt.thermal', $order) }}" class="btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
            Thermal 80mm
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
            Print Receipt
        </button>
    </div>

    {{-- Receipt card --}}
    <div class="receipt-card">

        {{-- Header --}}
        <div class="receipt-header">
            <div class="biz-name">{{ $businessName }}</div>
            @if($businessAddress)
                <div class="biz-sub">{{ $businessAddress }}</div>
            @endif
            @if($businessPhone)
                <div class="biz-sub">{{ $businessPhone }}</div>
            @endif
        </div>

        {{-- Order meta --}}
        <div class="order-meta">
            <div class="meta-group">
                <div class="meta-label">Order</div>
                <div class="meta-value">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="meta-group" style="text-align:center">
                <div class="meta-label">Status</div>
                <div><span class="badge">Paid</span></div>
            </div>
            <div class="meta-group" style="text-align:right">
                <div class="meta-label">Date</div>
                <div class="meta-value" style="font-size:12.5px">{{ $order->created_at->format('d M Y, h:i A') }}</div>
            </div>
        </div>

        <hr class="divider">

        {{-- Items --}}
        <div class="section">
            <div class="section-label">Order Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $it)
                        @php
                            $label = $it->product?->name ?? $it->menuItem?->name;
                            if (!$label) {
                                $label = 'Room Charge';
                                if ($booking) {
                                    $rt = $booking->rate_type ?? 'long';
                                    $label .= ' ('.ucfirst($rt).')';
                                }
                            }
                        @endphp
                        <tr>
                            <td><span class="qty-pill">{{ $it->quantity }}</span></td>
                            <td><span class="item-name">{{ $label }}</span></td>
                            <td>{{ $currency }} {{ number_format($it->price, 2) }}</td>
                            <td>{{ $currency }} {{ number_format($it->price * $it->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="divider">

        {{-- Totals --}}
        <div class="totals">
            <div class="trow">
                <span>Subtotal</span>
                <span class="tval">{{ $currency }} {{ number_format($subTotal, 2) }}</span>
            </div>
            @if($discount > 0)
            <div class="trow discount">
                <span>Discount</span>
                <span class="tval">{{ $currency }} {{ number_format($discount, 2) }}</span>
            </div>
            @endif
            @if($balRet > 0)
            <div class="trow">
                <span>Balance Returned</span>
                <span class="tval">{{ $currency }} {{ number_format($balRet, 2) }}</span>
            </div>
            @endif
            <div class="trow grand">
                <span>Total</span>
                <span class="tval">{{ $currency }} {{ number_format($total, 2) }}</span>
            </div>
        </div>

        <hr class="divider">

        {{-- Payments --}}
        <div class="section">
            <div class="section-label">Payment History</div>
            @if($order->payments->where('status','paid')->count())
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->payments->where('status','paid') as $pay)
                    <tr>
                        <td>{{ $currency }} {{ number_format($pay->amount, 2) }}</td>
                        <td><span class="method-tag">{{ ucwords(str_replace('_', ' ', $pay->method)) }}</span></td>
                        <td>{{ $pay->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-payments">No payments recorded yet.</div>
            @endif
        </div>

        {{-- Thank you + footer --}}
        <div class="thankyou">
            <strong>Thank you for your visit!</strong><br>
            We look forward to seeing you again.
        </div>

        <div class="receipt-footer">
            <span>Powered by {{ config('app.name') }}</span>
            <span>{{ $order->created_at->format('Y') }}</span>
        </div>

    </div>{{-- .receipt-card --}}</div>{{-- .page --}}
</body>
</html>