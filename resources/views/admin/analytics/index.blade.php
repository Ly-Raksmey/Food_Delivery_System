@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Analytics</h1>
        <p class="page-subtitle">View sales trends and performance metrics</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Sales Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="salesTrendChart" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Popular Foods</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Food Name</th>
                                <th>Category</th>
                                <th>Total Orders</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popular_foods ?? [] as $food)
                            <tr>
                                <td class="fw-semibold">{{ $food->name }}</td>
                                <td>{{ $food->category->name ?? 'N/A' }}</td>
                                <td>{{ $food->total_orders ?? 0 }}</td>
                                <td>${{ number_format($food->total_revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No data available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($sales_labels ?? []),
            datasets: [{
                label: 'Sales',
                data: @json($sales_data ?? []),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'pie',
        data: {
            labels: @json($category_labels ?? []),
            datasets: [{
                data: @json($category_data ?? []),
                backgroundColor: ['#16a34a', '#15803d', '#22c55e', '#4ade80', '#86efac', '#bbf7d0', '#059669']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endsection
