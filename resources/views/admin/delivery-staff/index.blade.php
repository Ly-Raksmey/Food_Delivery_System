@extends('layouts.admin')

@section('title', 'Delivery Staff')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Delivery Staff</h1>
        <p class="page-subtitle">Manage your delivery team members</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStaffModal">
        <i class="bi bi-plus-lg"></i> Add Staff
    </button>
</div>

<!-- CREATE STAFF MODAL -->
<div class="modal fade" id="createStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create Delivery Staff</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.delivery-staff.store') }}" method="POST" enctype="multipart/form-data" id="createStaffForm">
                    @csrf
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="create_name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="create_email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="create_phone" name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="create_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_status" class="form-label">Status</label>
                        <select class="form-select" id="create_status" name="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create_profile_image" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="create_profile_image" name="profile_image" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="createStaffForm" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Create Staff
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT STAFF MODAL -->
<div class="modal fade" id="editStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit Delivery Staff</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="editStaffForm">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_profile_image" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="edit_profile_image" name="profile_image" accept="image/*">
                        <div id="edit_profile_image_preview" class="mt-2" style="display: none;">
                            <img src="" alt="" id="edit_profile_image_img" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editStaffForm" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update Staff
                </button>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Delivery Staff</h6>
        <span class="badge bg-primary">{{ $deliveryStaff->count() ?? 0 }} members</span>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryStaff ?? [] as $staff)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($staff->profile_image)
                                    <img src="{{ asset('storage/' . $staff->profile_image) }}" alt="{{ $staff->name }}" class="avatar avatar-sm">
                                @else
                                    <div class="avatar avatar-sm avatar-primary">{{ substr($staff->name, 0, 1) }}</div>
                                @endif
                                <span class="fw-semibold">{{ $staff->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $staff->email }}</td>
                        <td class="text-muted">{{ $staff->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $staff->status == 'active' ? 'success' : ($staff->status == 'busy' ? 'warning' : 'secondary') }}">
                                <span class="status-dot {{ $staff->status == 'active' ? 'online' : ($staff->status == 'busy' ? 'busy' : 'offline') }}"></span>
                                {{ ucfirst($staff->status ?? 'inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button type="button" class="btn btn-outline-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#editStaffModal"
                                    data-id="{{ $staff->id }}"
                                    data-name="{{ $staff->name }}"
                                    data-email="{{ $staff->email }}"
                                    data-phone="{{ $staff->phone }}"
                                    data-status="{{ $staff->status }}"
                                    data-profile-image="{{ $staff->profile_image ? asset('storage/' . $staff->profile_image) : '' }}"
                                    data-url="{{ route('admin.delivery-staff.update', $staff) }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.delivery-staff.toggle', $staff) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $staff->status == 'active' ? 'warning' : 'success' }}" title="{{ $staff->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $staff->status == 'active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $staff->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="deleteModal{{ $staff->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Confirm Delete</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                                            <p class="mt-3">Are you sure you want to delete <strong>{{ $staff->name }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admin.delivery-staff.destroy', $staff) }}" method="POST" class="d-inline">
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
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-truck empty-state-icon"></i>
                                <p class="empty-state-title">No delivery staff yet</p>
                                <p class="empty-state-text">Add delivery staff members to fulfill orders.</p>
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
    const editModal = document.getElementById('editStaffModal');
    const editForm = document.getElementById('editStaffForm');
    const editImagePreview = document.getElementById('edit_profile_image_preview');
    const editImageImg = document.getElementById('edit_profile_image_img');

    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');
            const status = button.getAttribute('data-status');
            const profileImage = button.getAttribute('data-profile-image');
            const url = button.getAttribute('data-url');

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_status').value = status;
            editForm.action = url;

            if (profileImage) {
                editImageImg.src = profileImage;
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
