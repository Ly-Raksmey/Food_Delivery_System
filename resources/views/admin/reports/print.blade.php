<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - {{ ucfirst($period) }} - {{ $startDate->format('M d, Y') }} @if($timeFilter) - {{ ucfirst($timeFilter) }} @endif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            font-size: 13px;
            color: #212529;
        }
        .report-header {
            text-align: center;
            padding-bottom: 12px;
            border-bottom: 2px solid #000;
            margin-bottom: 16px;
        }
        .report-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .report-header .brand {
            color: #16a34a;
        }
        .report-header p {
            margin: 2px 0 0;
            color: #6c757d;
            font-size: 0.85rem;
        }
        .summary-row {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }
        .summary-box {
            flex: 1;
            border: 1px solid #000;
            padding: 10px 12px;
            background: #fff;
        }
        .summary-box .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .summary-box .value {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #dee2e6;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 0.85rem;
        }
        .report-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6c757d;
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .report-table td {
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        .report-table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .text-muted { color: #6c757d; }
        .text-success { color: #16a34a; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .fw-semibold { font-weight: 600; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 4px;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-info { background: #dcfce7; color: #15803d; }
        .badge-primary { background: #dcfce7; color: #15803d; }
        .badge-secondary { background: #e2e3e5; color: #41464b; }
        .badge-orange { background: #ffe5d0; color: #8a3a00; }
        .report-footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #000;
            text-align: center;
            color: #6c757d;
            font-size: 0.75rem;
        }
        .no-print { display: none !important; }
        @media print {
            body { padding: 0; margin: 0; }
            .report-header { border-bottom: 2px solid #000; }
            .summary-box { border: 1px solid #000; }
            .report-table th { background: #e9ecef !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="max-width: 900px; margin: 0 auto; padding: 16px;">
        <div class="report-header">
            <h2><i class="bi bi-basket2-fill me-2 brand"></i>FoodDelivery</h2>
            <p>{{ ucfirst($period) }} Report @if($timeFilter) - {{ ucfirst($timeFilter) }} @endif</p>
            <p>
                @if($period === 'daily')
                    {{ $startDate->format('d M Y') }}
                @elseif($period === 'monthly')
                    {{ $startDate->format('M Y') }}
                @else
                    {{ $startDate->format('Y') }}
                @endif
            </p>
            @if($timeFilter)
                <p>
                    @if($timeFilter === 'morning')
                        00:00 - 11:59
                    @elseif($timeFilter === 'evening')
                        12:00 - 17:59
                    @elseif($timeFilter === 'night')
                        18:00 - 23:59
                    @endif
                </p>
            @endif
        </div>

        <div class="summary-row">
            <div class="summary-box">
                <div class="label">Total Orders</div>
                <div class="value">{{ number_format($report_data['total_orders'] ?? 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Total Revenue</div>
                <div class="value text-success">${{ number_format($report_data['total_revenue'] ?? 0, 2) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Total Expenses</div>
                <div class="value text-danger">${{ number_format($report_data['total_expenses'] ?? 0, 2) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Net Profit</div>
                <div class="value {{ ($report_data['net_profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                    ${{ number_format($report_data['net_profit'] ?? 0, 2) }}
                </div>
            </div>
        </div>

        <div class="section-title">Detailed Report</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-end">Orders</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Expenses</th>
                    <th class="text-end">Profit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report_data['details'] ?? [] as $detail)
                <tr>
                    <td class="fw-semibold">{{ $detail['date'] ?? 'N/A' }}</td>
                    <td class="text-end">{{ $detail['orders'] ?? 0 }}</td>
                    <td class="text-end">${{ number_format($detail['revenue'] ?? 0, 2) }}</td>
                    <td class="text-end">${{ number_format($detail['expenses'] ?? 0, 2) }}</td>
                    <td class="text-end fw-semibold {{ ($detail['profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($detail['profit'] ?? 0, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No data available for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Orders in Selected Period ({{ $filteredOrders->count() }})</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Items</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredOrders ?? [] as $order)
                <tr>
                    <td class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ ($order->payment_status ?? '') == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'confirmed' ? 'primary' : ($order->order_status == 'preparing' ? 'info' : ($order->order_status == 'ready' ? 'secondary' : ($order->order_status == 'out_for_delivery' ? 'orange' : ($order->order_status == 'delivered' ? 'success' : 'danger'))))) }}">
                            {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')) }}
                        </span>
                    </td>
                    <td class="text-end">{{ $order->items->count() ?? 0 }}</td>
                    <td class="text-end fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No orders found for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="report-footer">
            <p>Generated on {{ now()->format('d M Y, h:i A') }} | FoodDelivery Admin Panel</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
