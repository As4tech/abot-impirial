@php
    $businessName    = function_exists('setting') ? (setting('general.business_name') ?: config('app.name')) : config('app.name');
    $businessContact = function_exists('setting') ? (setting('general.contact') ?: '') : '';
    $businessAddress = function_exists('setting') ? (setting('general.address') ?: $businessContact) : $businessContact;
    $businessPhone   = function_exists('setting') ? (setting('general.phone') ?: '') : '';
    $currency        = function_exists('setting') ? (setting('pos.currency') ?: 'PHP') : 'PHP';
    $booking         = \App\Models\Booking::where('order_id', $order->id)->first();
    $subTotal        = $order->total_amount;
    $discount        = $order->discount_amount ?? 0;
    $total           = $subTotal - $discount;
    $paidTotal       = $order->payments->where('status', 'paid')->sum('amount');
    $balanceReturned = max(0, $paidTotal - $total);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thermal Receipt #{{ $order->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.45;
            color: #000;
            background: #d9d9d9;
            padding: 20px 12px;
        }

        /* ── Preview toolbar ── */
        .no-print {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 7px 14px;
            border: 1px solid #888;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            color: #000;
            font-family: inherit;
        }
        .btn-print {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        /* ── Paper simulation (screen only) ── */
        .paper {
            width: 80mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.18), 0 0 0 1px rgba(0,0,0,.06);
            padding: 10px 10px 18px;
            position: relative;
        }

        /* ── Torn-edge effect bottom ── */
        .paper::after {
            content: '';
            display: block;
            position: absolute;
            bottom: -10px; left: 0; right: 0;
            height: 10px;
            background:
                radial-gradient(circle at 5px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 15px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 25px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 35px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 45px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 55px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 65px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 75px 0, #d9d9d9 5px, transparent 5px),
                radial-gradient(circle at 85mm 0, #d9d9d9 5px, transparent 5px);
            background-size: 10px 10px;
            background-repeat: repeat-x;
        }

        /* ── Header ── */
        .hdr { text-align: center; padding-bottom: 8px; }
        .biz-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .biz-tagline {
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #555;
            margin-top: 1px;
        }
        .biz-detail {
            font-size: 10px;
            color: #333;
            margin-top: 3px;
            line-height: 1.4;
        }

        /* ── Dividers ── */
        .div-solid  { border: none; border-top: 1px solid #000; margin: 7px 0; }
        .div-dash   { border: none; border-top: 1px dashed #999; margin: 6px 0; }
        .div-double {
            border: none;
            border-top: 3px double #000;
            margin: 7px 0;
        }

        /* ── Order meta ── */
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            line-height: 1.5;
            color: #222;
        }
        .meta-row .lbl { color: #555; }
        .order-num {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ── Items table ── */
        table { width: 100%; border-collapse: collapse; }
        .tbl-items thead tr th {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 3px 0 4px;
            border-bottom: 1px solid #000;
            color: #000;
        }
        .tbl-items tbody td {
            padding: 4px 0 3px;
            font-size: 10.5px;
            vertical-align: top;
            border-bottom: 1px dashed #ccc;
            color: #111;
        }
        .tbl-items tbody tr:last-child td { border-bottom: none; }

        .col-qty   { width: 8%;  }
        .col-name  { width: 44%; word-break: break-word; }
        .col-price { width: 22%; text-align: right; }
        .col-amt   { width: 26%; text-align: right; font-weight: bold; }

        .item-name { font-size: 10.5px; line-height: 1.35; }

        /* ── Totals ── */
        .totals { padding: 2px 0; }
        .trow {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            padding: 2px 0;
            color: #222;
        }
        .trow .lbl { color: #444; }
        .trow .val { font-family: 'Courier New', monospace; }
        .trow.discount .val { }
        .trow.grand {
            font-size: 14px;
            font-weight: bold;
            padding-top: 6px;
            margin-top: 2px;
            color: #000;
        }
        .trow.grand .val { font-size: 14px; }
        .trow.change {
            font-size: 10.5px;
            font-weight: bold;
        }

        /* ── Thank you block ── */
        .thankyou {
            text-align: center;
            padding: 6px 0 4px;
        }
        .thankyou .msg {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .thankyou .sub {
            font-size: 9px;
            color: #555;
            letter-spacing: .5px;
            margin-top: 2px;
        }

        /* ── Payments ── */
        .tbl-pay thead th {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 3px 0 4px;
            border-bottom: 1px solid #000;
            color: #000;
        }
        .tbl-pay tbody td {
            padding: 3px 0;
            font-size: 10px;
            color: #222;
            vertical-align: top;
        }
        .paid-pill {
            display: inline-block;
            border: 1px solid #000;
            padding: 0 4px;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: .5px;
            text-transform: uppercase;
            vertical-align: middle;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            margin-top: 8px;
        }
        .footer .powered {
            font-size: 8.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
        }
        .footer .order-id {
            font-size: 9px;
            color: #aaa;
            margin-top: 2px;
        }

        /* ── Barcode-style decorative line ── */
        .barcode-deco {
            display: flex;
            justify-content: center;
            gap: 1px;
            margin: 8px auto 4px;
            height: 18px;
            width: 120px;
        }
        .barcode-deco span {
            display: inline-block;
            background: #000;
            height: 100%;
        }

        /* ── Print rules ── */
        @media print {
            @page { size: 80mm auto; margin: 0; }
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .paper {
                width: 80mm;
                box-shadow: none;
                padding: 6px 8px 12px;
            }
            .paper::after { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <a href="{{ route('pos.receipt', $order) }}" class="btn">&#8592; Standard View</a>
    <button onclick="window.print()" class="btn btn-print">&#128438; Print 80mm</button>
</div>

<div class="paper">

    {{-- Business header --}}
    <div class="hdr">
        <div class="biz-name">{{ $businessName }}</div>
        @if($businessAddress)
            <div class="biz-detail">{{ $businessAddress }}</div>
        @endif
        @if($businessPhone)
            <div class="biz-detail">Tel: {{ $businessPhone }}</div>
        @endif
    </div>

    <hr class="div-solid">

    {{-- Order meta --}}
    <div class="meta-row">
        <div>
            <div class="lbl">ORDER</div>
            <div class="order-num">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div style="text-align:right">
            <div class="lbl">DATE / TIME</div>
            <div>{{ $order->created_at->format('d/m/Y') }}</div>
            <div>{{ $order->created_at->format('h:i A') }}</div>
        </div>
    </div>

    <hr class="div-dash">

    {{-- Items --}}
    <table class="tbl-items">
        <thead>
            <tr>
                <th class="col-qty">QTY</th>
                <th class="col-name">ITEM</th>
                <th class="col-price">PRICE</th>
                <th class="col-amt">AMT</th>
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
                    <td class="col-qty">{{ $it->quantity }}</td>
                    <td class="col-name"><span class="item-name">{{ $label }}</span></td>
                    <td class="col-price">{{ number_format($it->price, 2) }}</td>
                    <td class="col-amt">{{ number_format($it->price * $it->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="div-dash">

    {{-- Totals --}}
    <div class="totals">
        <div class="trow">
            <span class="lbl">Subtotal</span>
            <span class="val">{{ $currency }} {{ number_format($subTotal, 2) }}</span>
        </div>
        @if($discount > 0)
        <div class="trow discount">
            <span class="lbl">Discount</span>
            <span class="val">- {{ $currency }} {{ number_format($discount, 2) }}</span>
        </div>
        @endif
        @if($balanceReturned > 0)
        <div class="trow change">
            <span class="lbl">Change</span>
            <span class="val">{{ $currency }} {{ number_format($balanceReturned, 2) }}</span>
        </div>
        @endif
    </div>

    <hr class="div-double">

    <div class="trow grand">
        <span>TOTAL</span>
        <span class="val">{{ $currency }} {{ number_format($total, 2) }}</span>
    </div>

    <hr class="div-solid" style="margin-top:8px">

    {{-- Payments --}}
    <table class="tbl-pay">
        <thead>
            <tr>
                <th style="width:32%">AMOUNT</th>
                <th style="width:35%">METHOD</th>
                <th style="width:33%;text-align:right">TIME</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->payments->where('status', 'paid') as $pay)
                <tr>
                    <td>{{ $currency }} {{ number_format($pay->amount, 2) }}</td>
                    <td>
                        <span class="paid-pill">PAID</span>
                        {{ ucwords(str_replace('_', ' ', $pay->method)) }}
                    </td>
                    <td style="text-align:right">{{ $pay->created_at->format('h:i A') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="padding:4px 0;color:#888">No payments recorded.</td></tr>
            @endforelse
        </tbody>
    </table>

    <hr class="div-dash">

    {{-- Thank you --}}
    <div class="thankyou">
        <div class="msg">Thank You!</div>
        <div class="sub">We appreciate your business</div>
    </div>

    {{-- Decorative barcode lines --}}
    <div class="barcode-deco" aria-hidden="true">
        @php
            $widths = [2,1,3,1,2,1,1,2,1,3,1,2,1,2,3,1,1,2,1,2,1,3,1,1,2];
        @endphp
        @foreach($widths as $w)
            <span style="width:{{ $w }}px"></span>
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="powered">{{ config('app.name') }} &bull; POS System</div>
        <div class="order-id">Ref: {{ strtoupper(substr(md5($order->id), 0, 10)) }}</div>
    </div>

</div>{{-- .paper --}}

</body>
</html>