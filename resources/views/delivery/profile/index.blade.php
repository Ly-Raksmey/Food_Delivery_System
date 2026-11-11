@extends('layouts.delivery')

@section('title', 'My Profile')
@section('topbar_title', 'My Profile')
@section('topbar_subtitle', 'Manage your delivery profile')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="delivery-card text-center">
            <div class="delivery-card-body py-5">
                <div class="delivery-profile-avatar mx-auto mb-3">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                    @else
                        {{ substr($user->name, 0, 1) }}
                    @endif
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">Delivery Driver</p>
                <span class="delivery-badge delivery-badge-delivered"><i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Online</span>
                <div class="d-grid gap-2 mt-4">
                    <a href="#" class="delivery-btn delivery-btn-primary">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="#" class="delivery-btn delivery-btn-ghost">
                        <i class="bi bi-power"></i> Go Offline
                    </a>
                </div>
            </div>
        </div>

        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Driver Details</h5>
            </div>
            <div class="delivery-card-body">
                <div class="delivery-info-row">
                    <span class="text-muted"><i class="bi bi-telephone me-1"></i> Phone</span>
                    <span class="fw-semibold">{{ $user->phone ?? 'N/A' }}</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted"><i class="bi bi-envelope me-1"></i> Email</span>
                    <span class="fw-semibold">{{ $user->email }}</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted"><i class="bi bi-bicycle me-1"></i> Vehicle</span>
                    <span class="fw-semibold">Motorbike</span>
                </div>
                <div class="delivery-info-row">
                    <span class="text-muted"><i class="bi bi-hash me-1"></i> Plate</span>
                    <span class="fw-semibold">1A-1234</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="delivery-card">
            <div class="delivery-card-header">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Update Profile</h5>
            </div>
            <div class="delivery-card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('delivery.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                        @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="delivery-btn delivery-btn-primary">
                        <i class="bi bi-save me-1"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_image');
    const preview = document.getElementById('profilePreview');
    const placeholder = document.getElementById('profilePlaceholder');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
