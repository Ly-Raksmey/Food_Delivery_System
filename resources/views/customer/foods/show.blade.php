@extends('layouts.customer')

@section('title', $food->name)

@section('content')
<section class="customer-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foods.index') }}">Foods</a></li>
                @if($food->category)
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $food->category->id) }}">{{ $food->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $food->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="food-detail-image">
                    <img src="{{ $food->image ? asset('storage/' . $food->image) : asset('images/placeholder-food.jpg') }}" alt="{{ $food->name }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="food-detail-info">
                    <span class="badge bg-light text-primary border mb-2">{{ $food->category->name ?? 'General' }}</span>
                    <h1 class="food-detail-title">{{ $food->name }}</h1>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        @php
                            $rating = $food->reviews->avg('rating') ?? 0;
                            $fullStars = floor($rating);
                            $halfStar = $rating - $fullStars >= 0.5;
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i == $fullStars + 1 && $halfStar)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                        <span class="text-muted small">({{ $food->reviews->count() }} reviews)</span>
                    </div>

                    <h2 class="food-detail-price">${{ number_format($food->price, 2) }}</h2>
                    <p class="food-detail-desc">{{ $food->description }}</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-3 mb-4">
                        @csrf
                        <input type="hidden" name="food_id" value="{{ $food->id }}">
                        <div class="quantity-input">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                            <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $food->stock ?? 99 }}" style="width:60px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </form>

                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-clock me-1"></i> 15-20 min</span>
                        <span><i class="bi bi-fire me-1"></i> {{ $food->calories ?? 'N/A' }} cal</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5 pt-4 border-top">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Customer Reviews</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @auth
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Write a Review</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-warning" for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">Please <a href="{{ route('login') }}">login</a> to write a review.</div>
                @endauth

                @forelse($reviews ?? [] as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill text-warning small"></i>
                                            @else
                                                <i class="bi bi-star text-muted small"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 mt-2 text-muted">{{ $review->comment }}</p>
                            @if($review->admin_reply)
                                <div class="mt-3 p-3 bg-light rounded border-start border-4 border-primary">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-shield-check text-primary"></i>
                                        <strong class="small">Admin Reply</strong>
                                        <small class="text-muted">{{ $review->admin_reply_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted">{{ $review->admin_reply }}</p>
                                </div>
                            @endif
                            @auth
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleReplyForm({{ $review->id }})">
                                        <i class="bi bi-reply me-1"></i> Reply
                                    </button>
                                    @if(Auth::user()->id === $review->user_id)
                                        <button class="btn btn-sm btn-outline-warning" onclick="editReview({{ $review->id }}, '{{ addslashes($review->comment) }}', {{ $review->rating }})">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div id="replyForm{{ $review->id }}" class="mt-3 d-none">
                                    <form action="{{ route('reviews.reply', $review) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea class="form-control form-control-sm" name="comment" rows="2" placeholder="Write your reply..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Submit Reply</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleReplyForm({{ $review->id }})">Cancel</button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>

                    @if($review->replies->count() > 0)
                        <div class="ms-4 mb-3 border-start border-3 ps-3" style="border-color: var(--border) !important;">
                            @foreach($review->replies as $reply)
                                <div class="card p-3 mb-3 shadow-sm border-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold mb-0">{{ $reply->user->name ?? 'Anonymous' }}</h6>
                                            <small class="text-muted">Replied</small>
                                        </div>
                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 mt-2 text-muted">{{ $reply->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @empty
                    <div class="empty-state">
                        <i class="bi bi-star empty-state-icon"></i>
                        <p class="empty-state-title">No reviews yet</p>
                        <p class="empty-state-text">Be the first to review this food!</p>
                    </div>
                @endforelse
            </div>
        </div>
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
    function incrementQuantity() {
        var input = document.getElementById('quantity');
        var max = parseInt(input.getAttribute('max')) || 99;
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decrementQuantity() {
        var input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function editReview(id, comment, rating) {
        document.getElementById('editReviewForm').action = '/reviews/' + id;
        document.getElementById('editComment').value = comment;
        document.getElementById('editRating').value = rating;
        var modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
        modal.show();
    }

    function toggleReplyForm(reviewId) {
        var form = document.getElementById('replyForm' + reviewId);
        if (form) {
            form.classList.toggle('d-none');
        }
    }
</script>
@endsection
