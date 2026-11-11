
 
@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="page-header">

    <div>

        <h1 class="page-title">
            Categories
        </h1>

        <p class="page-subtitle">
            Organize your menu into categories
        </p>

    </div>


    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createCategoryModal">

        <i class="bi bi-plus-lg"></i>

        Add Category

    </button>

</div>


<!-- =========================================================
      CREATE CATEGORY MODAL
========================================================= -->

<div
    class="modal fade"
    id="createCategoryModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Create Category
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form
                    action="{{ route('admin.categories.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="createCategoryForm">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="create_name"
                            class="form-label">

                            Category Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="create_name"
                            name="name"
                            value="{{ old('name') }}"
                            required>

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
                            rows="3">{{ old('description') }}</textarea>

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
                    form="createCategoryForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Create Category

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
      EDIT CATEGORY MODAL
========================================================= -->

<div
    class="modal fade"
    id="editCategoryModal"
    tabindex="-1">

    <div
        class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h6 class="modal-title">
                    Edit Category
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
                    id="editCategoryForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="edit_name"
                            class="form-label">

                            Category Name

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
                    form="editCategoryForm"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Update Category

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
      DELETE CATEGORY MODAL
      Kept outside the table/cell so row overflow and transforms
      cannot clip or shift the fixed-position modal.
========================================================= -->

<div
    class="modal fade"
    id="deleteCategoryModal"
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

                    <strong id="deleteCategoryName"></strong>?

                </p>

            </div>

            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <form
                    action=""
                    method="POST"
                    class="d-inline"
                    id="deleteCategoryForm">

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


<!-- =========================================================
      CATEGORIES CARD
========================================================= -->

<div class="data-card">

    <div class="data-card-header">

        <h6 class="data-card-title">
            All Categories
        </h6>

        <div class="d-flex align-items-center gap-2">

            <span class="badge bg-primary">

                {{ $categories->count() ?? 0 }}

                total

            </span>

            <div class="admin-view-toggle">
                <a href="{{ route('admin.categories.index') }}" class="admin-view-btn {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" title="List View">
                    <i class="bi bi-list-ul"></i>
                </a>
                <a href="{{ route('admin.categories.grid') }}" class="admin-view-btn {{ request()->routeIs('admin.categories.grid') ? 'active' : '' }}" title="Card View">
                    <i class="bi bi-grid"></i>
                </a>
            </div>

        </div>

    </div>


    <div class="data-card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>
                            Image
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($categories ?? [] as $category)

                        <tr>

                            <!-- IMAGE -->

                            <td>

                                @if($category->image)

                                    <img
                                        src="{{ asset('storage/' . $category->image) }}"
                                        alt="{{ $category->name }}"
                                        class="img-thumb-md">

                                @else

                                    <div
                                        class="img-thumb-md d-flex align-items-center justify-content-center bg-light">

                                        <i class="bi bi-image text-muted fs-4"></i>

                                    </div>

                                @endif

                            </td>


                            <!-- NAME -->

                            <td class="fw-semibold">

                                {{ $category->name }}

                            </td>


                            <!-- DESCRIPTION -->

                            <td class="text-muted">

                                {{ Str::limit(
                                    $category->description ?? 'No description',
                                    50
                                ) }}

                            </td>


                            <!-- STATUS -->

                            <td>

                                <span
                                    class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">

                                    {{ ucfirst(
                                        $category->status ?? 'inactive'
                                    ) }}

                                </span>

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="action-btns">

                                    <!-- EDIT -->

                                    <button
                                        type="button"
                                        class="btn btn-outline-primary ml-auto"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}"
                                        data-image="{{ $category->image ? asset('storage/' . $category->image) : '' }}"
                                        data-status="{{ $category->status }}"
                                        data-url="{{ route('admin.categories.update', $category) }}">

                                        <i class="bi bi-pencil"></i>

                                    </button>


                                    <!-- DELETE -->

                                    {{-- <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        title="Delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCategoryModal"
                                        data-name="{{ $category->name }}"
                                        data-url="{{ route('admin.categories.destroy', $category) }}">

                                        <i class="bi bi-trash"></i>

                                    </button> --}}

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

                                    <div class="empty-state-icon">

                                        <i class="bi bi-collection"></i>

                                    </div>


                                    <p class="empty-state-title">

                                        No categories yet

                                    </p>


                                    <p class="empty-state-text">

                                        Create your first category to organize your menu.

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

    const editModal = document.getElementById('editCategoryModal');

    const editForm = document.getElementById('editCategoryForm');

    const editImagePreview = document.getElementById('edit_image_preview');

    const editImageImg = document.getElementById('edit_image_img');

    const deleteModal = document.getElementById('deleteCategoryModal');

    const deleteForm = document.getElementById('deleteCategoryForm');

    const deleteName = document.getElementById('deleteCategoryName');


    if (deleteModal && deleteForm && deleteName) {

        deleteModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            deleteName.textContent = button.getAttribute('data-name') || '';

            deleteForm.action = button.getAttribute('data-url') || '';

        });

    }


    if (editModal) {

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');

            const name = button.getAttribute('data-name');

            const description = button.getAttribute('data-description');

            const image = button.getAttribute('data-image');

            const status = button.getAttribute('data-status');

            const url = button.getAttribute('data-url');


            document.getElementById('edit_name').value = name;

            document.getElementById('edit_description').value = description || '';

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
