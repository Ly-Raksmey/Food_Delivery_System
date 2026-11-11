@extends('layouts.admin')

@section('title', 'Foods')

@section('content')

<div class="page-header">

    <div>

        <h1 class="page-title">
            Foods
        </h1>

        <p class="page-subtitle">
            Manage your menu items and pricing
        </p>

    </div>

    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createFoodModal">

        <i class="bi bi-plus-lg"></i>

        Add Food

    </button>

</div>


<div
    class="modal fade"
    id="createFoodModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Create Food
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form
                    action="{{ route('admin.foods.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="createFoodForm">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="create_name"
                            class="form-label">

                            Food Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="create_name"
                            name="name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_category_id"
                            class="form-label">

                            Category

                        </label>

                        <select
                            class="form-select"
                            id="create_category_id"
                            name="category_id"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories ?? [] as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_description"
                            class="form-label">

                            Description

                        </label>

                        <textarea
                            class="form-control"
                            id="create_description"
                            name="description"
                            rows="3"></textarea>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_price"
                            class="form-label">

                            Price ($)

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            id="create_price"
                            name="price"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="create_image"
                            class="form-label">

                            Image

                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="create_image"
                            name="image"
                            accept="image/*">

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

                            <option value="available" selected>
                                Available
                            </option>

                            <option value="unavailable">
                                Unavailable
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
                    form="createFoodForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Create Food

                </button>

            </div>

        </div>

    </div>

</div>


<div
    class="modal fade"
    id="editFoodModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Edit Food
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
                    enctype="multipart/form-data"
                    id="editFoodForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="edit_name"
                            class="form-label">

                            Food Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_name"
                            name="name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_category_id"
                            class="form-label">

                            Category

                        </label>

                        <select
                            class="form-select"
                            id="edit_category_id"
                            name="category_id"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories ?? [] as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_description"
                            class="form-label">

                            Description

                        </label>

                        <textarea
                            class="form-control"
                            id="edit_description"
                            name="description"
                            rows="3"></textarea>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_price"
                            class="form-label">

                            Price ($)

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            id="edit_price"
                            name="price"
                            required>

                    </div>

                    <div class="mb-3">

                        <label
                            for="edit_image"
                            class="form-label">

                            Image

                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="edit_image"
                            name="image"
                            accept="image/*">

                        <div
                            id="edit_image_preview"
                            class="mt-2"
                            style="display: none;">

                            <img
                                src=""
                                alt=""
                                id="edit_image_img"
                                class="img-thumbnail"
                                style="max-height: 100px;">

                        </div>

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

                            <option value="available">
                                Available
                            </option>

                            <option value="unavailable">
                                Unavailable
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
                    form="editFoodForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Update Food

                </button>

            </div>

        </div>

    </div>

</div>


<div class="filter-card">

<form
    action="{{ route('admin.foods.grid') }}"
    method="GET"
    class="row g-3 align-items-end">

    <div class="col-md-4">

        <label
            for="categoryFilter"
            class="form-label">

            Filter by Category

        </label>


        <select
            class="form-select"
            id="categoryFilter"
            name="category_id"
            onchange="this.form.submit()">

            <option value="">
                All Categories
            </option>


            @foreach($categories ?? [] as $category)

                <option
                    value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>

                    {{ $category->name }}

                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-2">

        <a
            href="{{ route('admin.foods.grid') }}"
            class="btn btn-outline-secondary w-100">

            <i class="bi bi-arrow-counterclockwise"></i>

            Reset

        </a>

    </div>

</form>

</div>


<div class="data-card">

    <div class="data-card-header">

        <h6 class="data-card-title">
            All Foods
        </h6>

        <div class="d-flex align-items-center gap-2">

            <span class="badge bg-primary">
                {{ $foods->count() ?? 0 }}
                items
            </span>

            <div class="admin-view-toggle">
                <a href="{{ route('admin.foods.index') }}" class="admin-view-btn {{ request()->routeIs('admin.foods.index') ? 'active' : '' }}" title="List View">
                    <i class="bi bi-list-ul"></i>
                </a>
                <a href="{{ route('admin.foods.grid') }}" class="admin-view-btn {{ request()->routeIs('admin.foods.grid') ? 'active' : '' }}" title="Card View">
                    <i class="bi bi-grid"></i>
                </a>
            </div>

        </div>

    </div>

    <div class="data-card-body">

        <div class="admin-foods-grid">

            @forelse($foods ?? [] as $food)

                <div class="admin-food-card">

                    <div class="admin-food-card-image">

                        @if($food->image)

                            <img
                                src="{{ asset('storage/' . $food->image) }}"
                                alt="{{ $food->name }}">

                        @else

                            <div
                                class="admin-food-card-placeholder">

                                <i class="bi bi-image"></i>

                            </div>

                        @endif

                        <span
                            class="badge bg-{{ $food->status == 'available' ? 'success' : 'danger' }} admin-food-card-status">

                            {{ ucfirst($food->status ?? 'unavailable') }}

                        </span>

                    </div>

                    <div class="admin-food-card-body">

                        <h6 class="admin-food-card-title">

                            {{ $food->name }}

                        </h6>

                        <p class="admin-food-card-category">

                            {{ $food->category->name ?? 'N/A' }}

                        </p>

                        <p class="admin-food-card-price">

                            ${{ number_format($food->price ?? 0, 2) }}

                        </p>

                        <div class="admin-food-card-actions">

                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                title="Edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editFoodModal"
                                data-id="{{ $food->id }}"
                                data-name="{{ $food->name }}"
                                data-category-id="{{ $food->category_id }}"
                                data-description="{{ $food->description }}"
                                data-price="{{ $food->price }}"
                                data-image="{{ $food->image ? asset('storage/' . $food->image) : '' }}"
                                data-status="{{ $food->status }}"
                                data-url="{{ route('admin.foods.update', $food) }}">

                                <i class="bi bi-pencil"></i>

                                Edit

                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm"
                                title="Delete"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $food->id }}">

                                <i class="bi bi-trash"></i>

                                Delete

                            </button>

                        </div>

                    </div>

                    <div
                        class="modal fade"
                        id="deleteModal{{ $food->id }}"
                        tabindex="-1">

                        <div
                            class="modal-dialog modal-sm modal-dialog-centered">

                            <div class="modal-content">

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

                                <div class="modal-body text-center">

                                    <i
                                        class="bi bi-exclamation-triangle text-warning fs-1">
                                    </i>

                                    <p class="mt-3 mb-0">

                                        Are you sure you want to delete

                                        <strong>
                                            {{ $food->name }}
                                        </strong>?

                                    </p>

                                </div>

                                <div
                                    class="modal-footer justify-content-center">

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">

                                        Cancel

                                    </button>

                                    <form
                                        action="{{ route('admin.foods.destroy', $food) }}"
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

                </div>

            @empty

                <div class="admin-foods-empty">

                    <div class="empty-state">

                        <div class="empty-state-icon">

                            <i class="bi bi-egg-fried"></i>

                        </div>

                        <p class="empty-state-title">

                            No foods yet

                        </p>

                        <p class="empty-state-text">

                            Add your first food item to start building your menu.

                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        {{ $foods->links() }}

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editFoodModal');

    const editForm = document.getElementById('editFoodForm');

    const editImagePreview = document.getElementById('edit_image_preview');

    const editImageImg = document.getElementById('edit_image_img');


    if (editModal) {

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');

            const name = button.getAttribute('data-name');

            const categoryId = button.getAttribute('data-category-id');

            const description = button.getAttribute('data-description');

            const price = button.getAttribute('data-price');

            const image = button.getAttribute('data-image');

            const status = button.getAttribute('data-status');

            const url = button.getAttribute('data-url');


            document.getElementById('edit_name').value = name;

            document.getElementById('edit_category_id').value = categoryId || '';

            document.getElementById('edit_description').value = description || '';

            document.getElementById('edit_price').value = price;

            document.getElementById('edit_status').value = status;

            editForm.action = url;


            if (image) {

                editImageImg.src = image;

                editImagePreview.style.display = 'block';

            } else {

                editImagePreview.style.display = 'none';

                editImageImg.src = '';

            }

        });


        editModal.addEventListener('hidden.bs.modal', function () {

            editForm.reset();

            editImagePreview.style.display = 'none';

            editImageImg.src = '';

        });

    }

});

</script>

@endsection
