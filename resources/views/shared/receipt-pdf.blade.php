<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_number }}</title>
    <style>
        /* Self-contained: Dompdf cannot load external stylesheets. */
        @page {
            margin: 6mm 5mm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
            color: #1e293b;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .muted { color: #64748b; }

        /* ---- Header ---- */
        .rc-head {
            text-align: center;
            padding-bottom: 3mm;
            border-bottom: 1px dashed #cbd5e1;
        }

        .rc-brand {
            font-size: 12pt;
            font-weight: bold;
            color: #285C32;
            letter-spacing: 0.3px;
        }

        .rc-sub {
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
        }

        /* ---- Section labels ---- */
        .rc-label {
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #64748b;
        }

        .rc-value {
            font-size: 8.5pt;
            font-weight: bold;
        }

        /* ---- Key/value grid ---- */
        table.rc-grid {
            width: 100%;
            border-collapse: collapse;
        }

        table.rc-grid td {
            padding: 1.1mm 0;
            vertical-align: top;
        }

        table.rc-grid td.k {
            width: 50%;
            padding-right: 2mm;
        }

        /* ---- Status pill ---- */
        .pill {
            display: inline-block;
            padding: 0.3mm 1.4mm;
            font-size: 6.2pt;
            font-weight: bold;
            color: #fff;
            background: #64748b;
            border-radius: 1.5mm;
        }

        /* ---- Sections ---- */
        .rc-section {
            margin-top: 2.6mm;
            padding-top: 1.8mm;
            border-top: 1px dashed #cbd5e1;
        }

        .rc-section-title {
            font-size: 6.2pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin-bottom: 1mm;
        }

        /* ---- Items table ---- */
        table.rc-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5mm;
        }

        table.rc-items th {
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            background: #f1f5f9;
            padding: 1.1mm 1.2mm;
            text-align: left;
        }

        table.rc-items td {
            font-size: 8pt;
            padding: 1.1mm 1.2mm;
            border-bottom: 1px solid #f1f5f9;
        }

        table.rc-items th.n, table.rc-items td.n { text-align: right; white-space: nowrap; }
        table.rc-items th.c, table.rc-items td.c { text-align: center; }

        /* ---- Totals ---- */
        table.rc-totals {
            width: 100%;
            border-collapse: collapse;
        }

        table.rc-totals td {
            padding: 0.7mm 0;
        }

        table.rc-totals tr.grand td {
            font-size: 10pt;
            font-weight: bold;
            padding-top: 1.4mm;
        }

        .discount { color: #059669; }

        /* ---- Footer ---- */
        .rc-foot {
            margin-top: 3mm;
            padding-top: 2mm;
            border-top: 1px dashed #cbd5e1;
            text-align: center;
            font-size: 6.5pt;
            color: #64748b;
        }

        .rc-thanks {
            font-size: 7.5pt;
            font-weight: bold;
            color: #285C32;
            margin-bottom: 0.8mm;
        }
    </style>
</head>
<body>

@php
    $statusText = ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending'));
    $statusColors = [
        'pending' => '#d97706',
        'confirmed' => '#285C32',
        'preparing' => '#0891b2',
        'ready' => '#64748b',
        'out_for_delivery' => '#ea580c',
        'delivered' => '#059669',
    ];
    $statusColor = $statusColors[$order->order_status ?? ''] ?? '#dc2626';
    $paymentColor = ($order->payment_status ?? '') === 'paid' ? '#059669' : '#d97706';
@endphp

<div class="rc-head">
    <div class="rc-brand">FoodDelivery</div>
    <div class="rc-sub">Order Receipt</div>
</div>

<table class="rc-grid" style="margin-top: 2.5mm;">
    <tr>
        <td class="k">
            <div class="rc-label">Order Number</div>
            <div class="rc-value">#{{ $order->order_number ?? 'N/A' }}</div>
        </td>
        <td>
            <div class="rc-label">Date</div>
            <div class="rc-value">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</div>
        </td>
    </tr>
    <tr>
        <td class="k">
            <div class="rc-label">Order Status</div>
            <div><span class="pill" style="background: {{ $statusColor }};">{{ $statusText }}</span></div>
        </td>
        <td>
            <div class="rc-label">Payment Status</div>
            <div><span class="pill" style="background: {{ $paymentColor }};">{{ ucfirst($order->payment_status ?? 'pending') }}</span></div>
        </td>
    </tr>
    <tr>
        <td class="k">
            <div class="rc-label">Payment Method</div>
            <div class="rc-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</div>
        </td>
        <td></td>
    </tr>
</table>

@if($order->user)
    <div class="rc-section">
        <div class="rc-section-title">Customer Information</div>
        <table class="rc-grid">
            <tr>
                <td class="k">
                    <div class="rc-label">Name</div>
                    <div class="rc-value">{{ $order->user->name ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="rc-label">Phone</div>
                    <div class="rc-value">{{ $order->user->phone ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td class="k" colspan="2">
                    <div class="rc-label">Email</div>
                    <div class="rc-value">{{ $order->user->email ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endif

@if($order->address)
    <div class="rc-section">
        <div class="rc-section-title">Delivery Address</div>
        <table class="rc-grid">
            <tr>
                <td class="k" colspan="2">
                    <div class="rc-label">Address</div>
                    <div class="rc-value">{{ $order->address->address ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td class="k">
                    <div class="rc-label">City</div>
                    <div class="rc-value">{{ $order->address->city ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="rc-label">Phone</div>
                    <div class="rc-value">{{ $order->address->phone ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endif

<div class="rc-section">
    <div class="rc-section-title">Order Items</div>
    <table class="rc-items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="c" style="width: 14mm;">Qty</th>
                <th class="n" style="width: 22mm;">Price</th>
                <th class="n" style="width: 24mm;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items ?? [] as $item)
                <tr>
                    <td>{{ $item->food->name ?? 'Unknown Item' }}</td>
                    <td class="c">{{ $item->quantity ?? 0 }}</td>
                    <td class="n">${{ number_format($item->price ?? 0, 2) }}</td>
                    <td class="n">${{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center muted" style="padding: 2.5mm 0;">No items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="rc-section">
    <table class="rc-totals">
        <tr>
            <td class="muted">Subtotal</td>
            <td class="right">${{ number_format($order->subtotal ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="muted">Delivery Fee</td>
            <td class="right">${{ number_format($order->delivery_fee ?? 0, 2) }}</td>
        </tr>
        @if(($order->discount_amount ?? 0) > 0)
            <tr>
                <td class="discount">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                <td class="right discount">-${{ number_format($order->discount_amount ?? 0, 2) }}</td>
            </tr>
        @endif
        <tr class="grand">
            <td>Total</td>
            <td class="right">${{ number_format($order->total_amount ?? 0, 2) }}</td>
        </tr>
    </table>
</div>

@if($delivery)
    <div class="rc-section">
        <div class="rc-section-title">Delivery Information</div>
        <table class="rc-grid">
            <tr>
                <td class="k">
                    <div class="rc-label">Delivery Status</div>
                    <div class="rc-value">{{ ucfirst(str_replace('_', ' ', $delivery->status ?? 'pending')) }}</div>
                </td>
                <td>
                    <div class="rc-label">Delivery Staff</div>
                    <div class="rc-value">{{ $delivery->deliveryStaff->name ?? 'Unassigned' }}</div>
                </td>
            </tr>
            @if($delivery->delivered_at)
                <tr>
                    <td class="k" colspan="2">
                        <div class="rc-label">Delivered At</div>
                        <div class="rc-value">{{ \Carbon\Carbon::parse($delivery->delivered_at)->format('d M Y, h:i A') }}</div>
                    </td>
                </tr>
            @endif
        </table>
    </div>
@endif

@if($order->notes)
    <div class="rc-section">
        <div class="rc-section-title">Notes</div>
        <div class="muted">{{ $order->notes }}</div>
    </div>
@endif

<div class="rc-foot">
    <div class="rc-thanks">Thank you for your order!</div>
    <div>Generated {{ now()->format('d M Y, h:i A') }}</div>
</div>

</body>
</html>
