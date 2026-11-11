<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Report - {{ Auth::user()->name ?? 'Driver' }}</title>
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
            color: #ff6b6b;
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
        .text-success { color: #198754; }
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
        .badge-success { background: #d1e7dd; color: #0f5132; }
        .badge-warning { background: #fff3cd; color: #664d03; }
        .badge-danger { background: #f8d7da; color: #842029; }
        .badge-info { background: #cff4fc; color: #055160; }
        .badge-primary { background: #cfe2ff; color: #084298; }
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
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Report
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i> Close
            </button>
        </div>

        <div class="report-header">
            <h2><i class="bi bi-truck me-2 brand"></i>FoodDelivery</h2>
            <p>Delivery Report - {{ Auth::user()->name ?? 'Driver' }}</p>
            <p>Period: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
            @if(request('status_filter'))
                <p>Status: {{ ucfirst(str_replace('_', ' ', request('status_filter'))) }}</p>
            @endif
        </div>

        <div class="summary-row">
            <div class="summary-box">
                <div class="label">Total Deliveries</div>
                <div class="value">{{ number_format($report_data['total_deliveries'] ?? 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Completed</div>
                <div class="value text-success">{{ number_format($report_data['completed'] ?? 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Pending</div>
                <div class="value text-warning">{{ number_format($report_data['pending'] ?? 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Total Earnings</div>
                <div class="value text-success">${{ number_format($report_data['earnings'] ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="section-title">Delivery History ({{ $reportDeliveries->count() }})</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportDeliveries ?? [] as $delivery)
                    @php
                        $order = $delivery->order;
                        $customer = $order->user ?? null;
                        $address = $order->address ?? null;
                        $statusBadge = match($delivery->status) {
                            'assigned' => 'secondary',
                            'accepted' => 'primary',
                            'picked_up' => 'info',
                            'out_for_delivery' => 'orange',
                            'delivered' => 'success',
                            default => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td class="fw-semibold">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td>{{ $customer->name ?? 'Unknown' }}</td>
                        <td><small>{{ $address->address ?? 'N/A' }}</small></td>
                        <td><span class="badge badge-{{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span></td>
                        <td>{{ $delivery->created_at ? $delivery->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td class="text-end fw-semibold">${{ number_format($order->total_amount ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No deliveries found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="report-footer">
            <p>Generated on {{ now()->format('d M Y, h:i A') }} | FoodDelivery Delivery Panel</p>
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