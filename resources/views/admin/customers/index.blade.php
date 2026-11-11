@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Customers</h1>
        <p class="page-subtitle">View and manage your customer base</p>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Customers</h6>
        <span class="badge bg-primary">{{ $customers->count() ?? 0 }} total</span>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers ?? [] as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($customer->name, 0, 1) }}</div>
                                <span class="fw-semibold">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $customer->email }}</td>
                        <td class="text-muted">{{ $customer->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $customer->role == 'customer' ? 'primary' : ($customer->role == 'admin' ? 'dark' : 'info') }}">
                                {{ ucfirst($customer->role) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people empty-state-icon"></i>
                                <p class="empty-state-title">No customers yet</p>
                                <p class="empty-state-text">Customers will appear here once they register.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
