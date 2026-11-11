@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')

<div class="page-header">

    <div>

        <h1 class="page-title">
            Expenses
        </h1>

        <p class="page-subtitle">
            Track and manage business expenses
        </p>

    </div>


    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createExpenseModal">

        <i class="bi bi-plus-lg"></i>

        Add Expense

    </button>

</div>


<!-- =========================================================
       CREATE EXPENSE MODAL
========================================================= -->

<div
    class="modal fade"
    id="createExpenseModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Create Expense
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">

                </button>

            </div>

            <div class="modal-body">

                <form
                    action="{{ route('admin.expenses.store') }}"
                    method="POST"
                    id="createExpenseForm">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="create_title"
                            class="form-label">

                            Title

                        </label>

                        <input
                            type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="create_title"
                            name="title"
                            value="{{ old('title') }}"
                            required>

                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_category"
                            class="form-label">

                            Category

                        </label>

                        <input
                            type="text"
                            class="form-control @error('category') is-invalid @enderror"
                            id="create_category"
                            name="category"
                            value="{{ old('category') }}"
                            placeholder="e.g. Utilities, Rent, Supplies">

                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_amount"
                            class="form-label">

                            Amount ($)

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control @error('amount') is-invalid @enderror"
                            id="create_amount"
                            name="amount"
                            value="{{ old('amount') }}"
                            required>

                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_description"
                            class="form-label">

                            Description

                        </label>

                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="create_description"
                            name="description"
                            rows="4">{{ old('description') }}</textarea>

                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_expense_date"
                            class="form-label">

                            Date

                        </label>

                        <input
                            type="date"
                            class="form-control @error('expense_date') is-invalid @enderror"
                            id="create_expense_date"
                            name="expense_date"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            required>

                        @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

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
                    form="createExpenseForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Create Expense

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
       EDIT EXPENSE MODAL
========================================================= -->

<div
    class="modal fade"
    id="editExpenseModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Edit Expense
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
                    id="editExpenseForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="edit_title"
                            class="form-label">

                            Title

                        </label>

                        <input
                            type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="edit_title"
                            name="title"
                            required>

                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_category"
                            class="form-label">

                            Category

                        </label>

                        <input
                            type="text"
                            class="form-control @error('category') is-invalid @enderror"
                            id="edit_category"
                            name="category"
                            placeholder="e.g. Utilities, Rent, Supplies">

                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_amount"
                            class="form-label">

                            Amount ($)

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control @error('amount') is-invalid @enderror"
                            id="edit_amount"
                            name="amount"
                            required>

                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_description"
                            class="form-label">

                            Description

                        </label>

                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="edit_description"
                            name="description"
                            rows="4"></textarea>

                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_expense_date"
                            class="form-label">

                            Date

                        </label>

                        <input
                            type="date"
                            class="form-control @error('expense_date') is-invalid @enderror"
                            id="edit_expense_date"
                            name="expense_date"
                            required>

                        @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

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
                    form="editExpenseForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Update Expense

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
       EXPENSES CARD
========================================================= -->

<div class="data-card">

    <div class="data-card-header">

        <h6 class="data-card-title">
            All Expenses
        </h6>

        <span class="badge bg-primary">
            {{ $expenses->count() ?? 0 }}
            total
        </span>

    </div>


    <div class="data-card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>
                            Title
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($expenses ?? [] as $expense)

                        <tr>

                            <!-- TITLE -->

                            <td class="fw-semibold">

                                {{ $expense->title }}

                            </td>


                            <!-- CATEGORY -->

                            <td>

                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst($expense->category ?? 'General') }}
                                </span>

                            </td>


                            <!-- AMOUNT -->

                            <td class="fw-semibold text-danger">

                                ${{ number_format($expense->amount ?? 0, 2) }}

                            </td>


                            <!-- DATE -->

                            <td class="text-muted">

                                {{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A' }}

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
                                        data-bs-target="#editExpenseModal"
                                        data-id="{{ $expense->id }}"
                                        data-title="{{ $expense->title }}"
                                        data-category="{{ $expense->category }}"
                                        data-amount="{{ $expense->amount }}"
                                        data-description="{{ $expense->description }}"
                                        data-expense-date="{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') : '' }}"
                                        data-url="{{ route('admin.expenses.update', $expense) }}">

                                        <i class="bi bi-pencil"></i>

                                    </button>


                                    <!-- DELETE -->

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        title="Delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $expense->id }}">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </div>


                                <!-- =================================================
                                     DELETE MODAL
                                ================================================= -->

                                <div
                                    class="modal fade"
                                    id="deleteModal{{ $expense->id }}"
                                    tabindex="-1">

                                    <div
                                        class="modal-dialog modal-sm modal-dialog-centered">

                                        <div class="modal-content">

                                            <!-- MODAL HEADER -->

                                            <div class="modal-header">

                                                <h6 class="modal-title">
                                                    Confirm Delete
                                                </h6>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">

                                                </button>

                                            </div>


                                            <!-- MODAL BODY -->

                                            <div class="modal-body text-center">

                                                <i
                                                    class="bi bi-exclamation-triangle text-warning fs-1">

                                                </i>

                                                <p class="mt-3 mb-0">

                                                    Are you sure you want to delete

                                                    <strong>
                                                        {{ $expense->title }}
                                                    </strong>?

                                                </p>

                                            </div>


                                            <!-- MODAL FOOTER -->

                                            <div class="modal-footer justify-content-center">

                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">

                                                    Cancel

                                                </button>

                                                <form
                                                    action="{{ route('admin.expenses.destroy', $expense) }}"
                                                    method="POST"
                                                    class="d-inline">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger">

                                                        Delete

                                                    </button>

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

                            <td colspan="5">

                                <div class="empty-state">

                                    <i class="bi bi-cash-stack empty-state-icon"></i>

                                    <p class="empty-state-title">
                                        No expenses yet
                                    </p>

                                    <p class="empty-state-text">
                                        Track your business expenses to monitor spending.
                                    </p>

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

    const editModal = document.getElementById('editExpenseModal');

    const editForm = document.getElementById('editExpenseForm');


    if (editModal) {

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');

            const title = button.getAttribute('data-title');

            const category = button.getAttribute('data-category');

            const amount = button.getAttribute('data-amount');

            const description = button.getAttribute('data-description');

            const expenseDate = button.getAttribute('data-expense-date');

            const url = button.getAttribute('data-url');


            document.getElementById('edit_title').value = title;

            document.getElementById('edit_category').value = category || '';

            document.getElementById('edit_amount').value = amount;

            document.getElementById('edit_description').value = description || '';

            document.getElementById('edit_expense_date').value = expenseDate;

            editForm.action = url;

        });


        editModal.addEventListener('hidden.bs.modal', function () {

            editForm.reset();

        });

    }

});

</script>

@endsection
