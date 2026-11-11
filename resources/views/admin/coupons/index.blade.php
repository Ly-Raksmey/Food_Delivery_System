@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Coupons</h1>
        <p class="page-subtitle">Create and manage discount coupons</p>
    </div>
    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createCouponModal">
        <i class="bi bi-plus-lg"></i> Add Coupon
    </button>
</div>


<!-- =========================================================
       CREATE COUPON MODAL
========================================================= -->

<div
    class="modal fade"
    id="createCouponModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Create Coupon
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form
                    action="{{ route('admin.coupons.store') }}"
                    method="POST"
                    id="createCouponForm">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="create_code"
                            class="form-label">

                            Coupon Code

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="create_code"
                            name="code"
                            value="{{ old('code') }}"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_type"
                            class="form-label">

                            Type

                        </label>

                        <select
                            class="form-select"
                            id="create_type"
                            name="type"
                            required>

                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                                Fixed Amount
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_value"
                            class="form-label">

                            Value

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            id="create_value"
                            name="value"
                            value="{{ old('value') }}"
                            required>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="create_minimum_order_amount"
                                class="form-label">

                                Minimum Order Amount ($)

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="create_minimum_order_amount"
                                name="minimum_order_amount"
                                value="{{ old('minimum_order_amount') }}">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="create_maximum_discount"
                                class="form-label">

                                Maximum Discount Amount ($)

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="create_maximum_discount"
                                name="maximum_discount"
                                value="{{ old('maximum_discount') }}">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="create_start_date"
                                class="form-label">

                                Start Date

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="create_start_date"
                                name="start_date"
                                value="{{ old('start_date') }}">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="create_end_date"
                                class="form-label">

                                End Date

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="create_end_date"
                                name="end_date"
                                value="{{ old('end_date') }}">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_usage_limit"
                            class="form-label">

                            Usage Limit

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="create_usage_limit"
                            name="usage_limit"
                            value="{{ old('usage_limit') }}">

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_status"
                            class="form-label">

                            Status

                        </label>

                        <select
                            class="form-select"
                            id="create_status"
                            name="status"
                            required>

                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="submit"
                    form="createCouponForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Create Coupon

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
       EDIT COUPON MODAL
========================================================= -->

<div
    class="modal fade"
    id="editCouponModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Edit Coupon
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form
                    action=""
                    method="POST"
                    id="editCouponForm">

                    @csrf

                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="edit_code"
                            class="form-label">

                            Coupon Code

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_code"
                            name="code"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_type"
                            class="form-label">

                            Type

                        </label>

                        <select
                            class="form-select"
                            id="edit_type"
                            name="type"
                            required>

                            <option value="percentage">
                                Percentage
                            </option>

                            <option value="fixed">
                                Fixed Amount
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_value"
                            class="form-label">

                            Value

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            id="edit_value"
                            name="value"
                            required>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="edit_minimum_order_amount"
                                class="form-label">

                                Minimum Order Amount ($)

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="edit_minimum_order_amount"
                                name="minimum_order_amount">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="edit_maximum_discount"
                                class="form-label">

                                Maximum Discount Amount ($)

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="edit_maximum_discount"
                                name="maximum_discount">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="edit_start_date"
                                class="form-label">

                                Start Date

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="edit_start_date"
                                name="start_date">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="edit_end_date"
                                class="form-label">

                                End Date

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="edit_end_date"
                                name="end_date">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_usage_limit"
                            class="form-label">

                            Usage Limit

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="edit_usage_limit"
                            name="usage_limit">

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_status"
                            class="form-label">

                            Status

                        </label>

                        <select
                            class="form-select"
                            id="edit_status"
                            name="status"
                            required>

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="submit"
                    form="editCouponForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Update Coupon

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
       COUPONS CARD
========================================================= -->

<div class="data-card">

    <div class="data-card-header">

        <h6 class="data-card-title">All Coupons</h6>

        <span class="badge bg-primary">{{ $coupons->count() ?? 0 }} total</span>

    </div>

    <div class="data-card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($coupons ?? [] as $coupon)

                        <tr>

                            <!-- CODE -->

                            <td>

                                <span class="fw-semibold font-monospace bg-light px-2 py-1 rounded" style="font-size:0.8rem; letter-spacing:0.05em; background: var(--green-light) !important;">{{ $coupon->code }}</span>

                            </td>

                            <!-- TYPE -->

                            <td>{{ ucfirst($coupon->type) }}</td>

                            <!-- VALUE -->

                            <td class="fw-semibold text-success">{{ $coupon->type == 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}</td>

                            <!-- START DATE -->

                            <td class="text-muted">{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('M d, Y') : 'N/A' }}</td>

                            <!-- END DATE -->

                            <td class="text-muted">{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('M d, Y') : 'N/A' }}</td>

                            <!-- USAGE -->

                            <td>

                                <span class="text-muted">{{ $coupon->used_count ?? 0 }} / {{ $coupon->usage_limit ?? '∞' }}</span>

                            </td>

                            <!-- STATUS -->

                            <td>

                                <span class="badge bg-{{ $coupon->status == 'active' ? 'success' : 'secondary' }}">

                                    {{ ucfirst($coupon->status ?? 'inactive') }}

                                </span>

                            </td>

                            <!-- ACTIONS -->

                            <td>

                                <div class="action-btns">

                                    <!-- EDIT -->

                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCouponModal"
                                        data-id="{{ $coupon->id }}"
                                        data-code="{{ $coupon->code }}"
                                        data-type="{{ $coupon->type }}"
                                        data-value="{{ $coupon->value }}"
                                        data-minimum-order-amount="{{ $coupon->minimum_order_amount }}"
                                        data-maximum-discount="{{ $coupon->maximum_discount }}"
                                        data-start-date="{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') : '' }}"
                                        data-end-date="{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') : '' }}"
                                        data-usage-limit="{{ $coupon->usage_limit }}"
                                        data-status="{{ $coupon->status }}"
                                        data-url="{{ route('admin.coupons.update', $coupon) }}">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                    <!-- TOGGLE -->

                                    <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $coupon->status == 'active' ? 'warning' : 'success' }}" title="{{ $coupon->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $coupon->status == 'active' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>

                                    <!-- DELETE -->

                                    <button type="button" class="btn btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $coupon->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>


                                <!-- =================================================
                                     DELETE MODAL
                                ================================================= -->

                                <div class="modal fade" id="deleteModal{{ $coupon->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Confirm Delete</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                                                <p class="mt-3">Are you sure you want to delete coupon <strong>{{ $coupon->code }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <!-- =================================================
                             EMPTY STATE
                        ================================================= -->

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">
                                    <i class="bi bi-ticket-perforated empty-state-icon"></i>
                                    <p class="empty-state-title">No coupons yet</p>
                                    <p class="empty-state-text">Create your first coupon to offer discounts to customers.</p>
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editCouponModal');

    const editForm = document.getElementById('editCouponForm');


    if (editModal) {

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');

            const code = button.getAttribute('data-code');

            const type = button.getAttribute('data-type');

            const value = button.getAttribute('data-value');

            const minimumOrderAmount = button.getAttribute('data-minimum-order-amount');

            const maximumDiscount = button.getAttribute('data-maximum-discount');

            const startDate = button.getAttribute('data-start-date');

            const endDate = button.getAttribute('data-end-date');

            const usageLimit = button.getAttribute('data-usage-limit');

            const status = button.getAttribute('data-status');

            const url = button.getAttribute('data-url');


            document.getElementById('edit_code').value = code;

            document.getElementById('edit_type').value = type;

            document.getElementById('edit_value').value = value;

            document.getElementById('edit_minimum_order_amount').value = minimumOrderAmount || '';

            document.getElementById('edit_maximum_discount').value = maximumDiscount || '';

            document.getElementById('edit_start_date').value = startDate || '';

            document.getElementById('edit_end_date').value = endDate || '';

            document.getElementById('edit_usage_limit').value = usageLimit || '';

            document.getElementById('edit_status').value = status;

            editForm.action = url;

        });


        editModal.addEventListener('hidden.bs.modal', function () {

            editForm.reset();

        });

    }

});

</script>

@endsection
