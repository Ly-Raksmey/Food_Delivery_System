@extends('layouts.customer')

@section('title', 'My Reviews')

@section('content')
<section class="customer-section">
    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Reviews</h1>
                <p class="page-subtitle">Your feedback on food items</p>
            </div>
        </div>

        @if($reviews->count() > 0)
            <div class="data-card">
                <div class="data-card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Food</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews ?? [] as $review)
                                <tr>
                                    <td class="fw-semibold">{{ $review->food->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= ($review->rating ?? 0) ? '-fill text-warning' : ' text-muted' }}" style="font-size:0.8rem;"></i>
                                            @endfor
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
                                    <td class="text-muted">{{ $review->created_at ? $review->created_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="{{ route('foods.show', $review->food_id) }}" class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-outline-warning" title="Edit" onclick="editReview({{ $review->id }}, '{{ addslashes($review->comment) }}', {{ $review->rating }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-star empty-state-icon"></i>
                                            <p class="empty-state-title">No reviews yet</p>
                                            <p class="empty-state-text">Your reviews will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($reviews->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-star empty-state-icon"></i>
                <p class="empty-state-title">No reviews yet</p>
                <p class="empty-state-text">Your reviews will appear here.</p>
            </div>
        @endif
    </div>
</section>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editReviewForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-input-edit" id="ratingStars"></div>
                        <input type="hidden" name="rating" id="editRating" value="5">
                    </div>
                    <div class="mb-3">
                        <label for="editComment" class="form-label">Comment</label>
                        <textarea class="form-control" id="editComment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editReview(id, comment, rating) {
        document.getElementById('editReviewForm').action = '/reviews/' + id;
        document.getElementById('editComment').value = comment;
        document.getElementById('editRating').value = rating;
        var modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
        modal.show();
    }
</script>
@endsection
