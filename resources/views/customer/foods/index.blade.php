@extends('layouts.customer')

@section('title', 'Menu')

@section('content')
<section class="sire-page">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sire-eyebrow">Explore</span>
            <h1 class="sire-section-title">Our <em>Menu</em></h1>
            <p class="sire-section-sub mx-auto" style="max-width: 520px;">Browse our full selection of fresh, healthy dishes prepared daily by Sire's chefs.</p>
        </div>

        <div class="sire-menu-filter mb-4">
            <form action="{{ route('foods.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-8 mx-auto">
                    <div class="input-group input-group-lg sire-search">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="search" placeholder="Search for a dish..." value="{{ request('search') }}">
                        <button class="sire-btn sire-btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('foods.index') }}" class="sire-chip {{ !request('category_id') ? 'active' : '' }}">All</a>
                @forelse($categories ?? [] as $category)
                    <a href="{{ route('foods.index', ['category_id' => $category->id]) }}" class="sire-chip {{ request('category_id') == $category->id ? 'active' : '' }}">
                        <i class="bi bi-{{ $category->icon ?? 'cup-hot' }}"></i> {{ $category->name }}
                    </a>
                @empty
                @endforelse
            </div>
        </div>

        @if(isset($foods) && $foods->count() > 0)
            <div class="row g-4">
                @foreach($foods as $food)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('foods.show', $food->id) }}" class="sire-dish-card text-decoration-none d-block">
                            <div class="sire-dish-image">
                                <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $food->name }}">
                                <span class="sire-dish-price">${{ number_format($food->price, 2) }}</span>
                            </div>
                            <div class="sire-dish-body">
                                <small class="text-muted mb-1 d-block">{{ $food->category->name ?? 'General' }}</small>
                                <h5 class="sire-dish-title">{{ $food->name }}</h5>
                                <p class="sire-dish-text">{{ \Illuminate\Support\Str::limit($food->description, 60) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if(method_exists($foods, 'links'))
                <div class="mt-5 d-flex justify-content-center">
                    {{ $foods->links() }}
                </div>
            @endif
        @else
            <div class="sire-empty">
                <i class="bi bi-egg-fried"></i>
                <h5>No dishes found</h5>
                <p>Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</section>
@endsection
