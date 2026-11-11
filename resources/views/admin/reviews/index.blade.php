@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Reviews</h1>
        <p class="page-subtitle">Manage customer reviews and ratings</p>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6 class="data-card-title">All Reviews</h6>
        <span class="badge bg-primary">{{ $reviews->count() ?? 0 }} total</span>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Food</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews ?? [] as $review)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ substr($review->user->name ?? 'U', 0, 1) }}</div>
                                <span class="fw-semibold">{{ $review->user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $review->food->name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= ($review->rating ?? 0) ? '-fill text-warning' : ' text-muted' }}" style="font-size:0.8rem;"></i>
                                @endfor
                                <span class="ms-1 fw-semibold" style="font-size:0.8rem;">{{ $review->rating ?? 0 }}</span>
                            </div>
                        </td>
                        <td class="text-muted" style="max-width:200px;">{{ Str::limit($review->comment ?? 'No comment', 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $review->status == 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($review->status ?? 'pending') }}
                            </span>
                            @if($review->admin_reply)
                                <span class="badge bg-info">Replied</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $review->status == 'active' ? 'warning' : 'success' }}" title="{{ $review->status == 'active' ? 'Hide' : 'Approve' }}">
                                        <i class="bi bi-{{ $review->status == 'active' ? 'pause' : 'check' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-primary" title="Reply" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                                    <i class="bi bi-reply"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $review->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Reply to Review</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="small text-muted mb-2">Review by <strong>{{ $review->user->name ?? 'N/A' }}</strong> on <strong>{{ $review->food->name ?? 'N/A' }}</strong></p>
                                                <p class="small text-muted mb-3">"{{ $review->comment ?? 'No comment' }}"</p>
                                                @if($review->admin_reply)
                                                    <div class="alert alert-info py-2 small">
                                                        <strong>Current reply:</strong> {{ $review->admin_reply }}
                                                        <br><small class="text-muted">Replied {{ $review->admin_reply_at->diffForHumans() }}</small>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="reply{{ $review->id }}" class="form-label">Your Reply</label>
                                                    <textarea class="form-control" id="reply{{ $review->id }}" name="admin_reply" rows="3" required>{{ $review->admin_reply }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Send Reply</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteModal{{ $review->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Confirm Delete</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                                            <p class="mt-3">Are you sure you want to delete this review?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
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
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-star empty-state-icon"></i>
                                <p class="empty-state-title">No reviews yet</p>
                                <p class="empty-state-text">Customer reviews will appear here once they rate their orders.</p>
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
