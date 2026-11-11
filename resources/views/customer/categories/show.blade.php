@extends('layouts.customer')

@section('title', $category->name)

@section('content')
<section class="customer-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foods.index') }}">Foods</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="text-center mb-5">
            <div class="category-hero-icon">
                <i class="bi bi-{{ $category->icon ?? 'cup-hot' }}"></i>
            </div>
            <h1 class="fw-bold">{{ $category->name }}</h1>
            <p class="text-muted">{{ $category->description ?? 'Explore our delicious selection' }}</p>
        </div>

        @if($foods->count() > 0)
            <div class="row g-3">
                @foreach($foods as $food)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="food-card">
                            <div class="food-card-image">
                                <img src="{{ $food->image ? asset('storage/' . $food->image) : asset('images/placeholder-food.jpg') }}" alt="{{ $food->name }}">
                            </div>
                            <div class="food-card-body">
                                <h5 class="food-card-title text-truncate">{{ $food->name }}</h5>
                                <p class="food-card-desc text-truncate">{{ $food->description }}</p>
                                <div class="food-card-footer">
                                    <span class="food-card-price">${{ number_format($food->price, 2) }}</span>
                                    <div class="food-card-actions">
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="food_id" value="{{ $food->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('foods.show', $food->id) }}" class="btn btn-outline-primary btn-sm" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(method_exists($foods, 'links'))
                <div class="mt-4 d-flex justify-content-center">
                    {{ $foods->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-egg-fried empty-state-icon"></i>
                <p class="empty-state-title">No foods available</p>
                <p class="empty-state-text">No foods in this category yet.</p>
            </div>
        @endif
    </div>
</section>
@endsection
