@extends('layouts.customer')

@section('title', 'Sire — Fresh & Healthy Food')

@section('content')
@php
    $featuredFoods = ($popularFoods ?? collect())->take(4);
    $specialDishes = $featuredFoods->count() >= 4
        ? $featuredFoods
        : ($popularFoods ?? collect())->concat($popularFoods ?? collect())->take(4);
    $categoriesList = $categories ?? collect();
@endphp

<!-- HERO -->
<section class="sire-hero">
    <span class="sire-leaf sire-leaf-1"><i class="bi bi-tree-fill"></i></span>
    <span class="sire-leaf sire-leaf-2"><i class="bi bi-flower1"></i></span>
    <span class="sire-leaf sire-leaf-3"><i class="bi bi-tree-fill"></i></span>

    <div class="container sire-hero-inner">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="sire-badge">🌿 Fresh &amp; Healthy</span>
                <h1 class="sire-hero-title">
                    Share du Haily<br>
                    <em>Future Food And Delivery</em>
                </h1>
                <p class="sire-hero-sub">At Sire we craft wholesome meals using farm-fresh ingredients, classic techniques, and a passion for flavor — served the way nature intended.</p>
                <div class="sire-hero-cta">
                    <a href="{{ route('foods.index') }}" class="sire-btn sire-btn-primary sire-btn-lg">
                        Order Now <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <button class="sire-btn sire-btn-ghost sire-btn-lg  " type="button" onclick="new bootstrap.Modal(document.getElementById('videoModal')).show()">
                        <span class="sire-play"><i class="bi bi-play-fill"></i></span>
                        Restaurant Video
                    </button>
                </div>
                <div class="sire-hero-stats">
                    <div><strong>250+</strong><span>Recipes</span></div>
                    <div><strong>30 min</strong><span>Delivery</span></div>
                    <div><strong>4.9★</strong><span>Rating</span></div>
                </div>
            </div>
        <style>

/* =========================
   HERO FOOD ANIMATION
========================= */

/* Watch Video Button */
.sire-btn-ghost {
    color: #164d2a;
    border: 1px solid #16a34a;
    background: transparent;
    transition:
        background-color 0.4s ease,
        color 0.4s ease,
        border-color 0.4s ease,
        transform 0.3s ease,
        box-shadow 0.4s ease;
}

.sire-btn-ghost:hover {
    color: #ffffff;
    background: #164d2a;
    border-color: #164d2a;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(22, 163, 74, 0.2);
}

.sire-play {
    color: #16a34a;
    transition: color 0.4s ease, transform 0.4s ease;
}

.sire-btn-ghost:hover .sire-play {
    color: #ffffff;
    transform: scale(1.15);
}


/* =========================
   HERO VISUAL
========================= */

.sire-hero-visual {
    position: relative;
    width: 100%;
    height: 600px;
    display: flex;
    align-items: center;
    justify-content: center;
}


/* Burger Circle */

.sire-hero-plate {
    position: relative;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;

    background: #ffffff;

    border: 8px solid #dceccf;

    box-shadow:
        0 25px 60px rgba(31, 80, 43, 0.15),
        0 0 0 18px rgba(220, 236, 207, 0.45);

    animation:
        burgerEnter 1.2s ease-out forwards,
        burgerFloat 4s ease-in-out 1.2s infinite;
}


/* Burger Image */

.sire-hero-plate img {
    width: 88%;
    height: 88%;
    object-fit: cover;
    border-radius: 50%;
    display: block;

    box-shadow:
        0 15px 35px rgba(0, 0, 0, 0.18);

    animation: burgerImage 4s ease-in-out infinite;
}


/* =========================
   FLOATING ANIMATION
========================= */

@keyframes burgerFloat {

    0% {
        transform: translateY(0px) rotate(0deg);
    }

    25% {
        transform: translateY(-10px) rotate(1deg);
    }

    50% {
        transform: translateY(-18px) rotate(0deg);
    }

    75% {
        transform: translateY(-10px) rotate(-1deg);
    }

    100% {
        transform: translateY(0px) rotate(0deg);
    }
}


/* Image Movement */

@keyframes burgerImage {

    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.03);
    }

    100% {
        transform: scale(1);
    }
}


/* =========================
   GOOD FOOD GOOD MOOD
========================= */

.sire-handwritten-1 {
    position: absolute;
    right: 5%;
    bottom: 65px;

    color: #164d2a;

    font-family: Georgia, serif;
    font-size: 30px;
    font-weight: bold;
    font-style: italic;
    line-height: 1.1;

    animation: textFloat 3s ease-in-out infinite;
}

.sire-handwritten-1 em {
    font-weight: 500;
}


/* =========================
   LEAF
========================= */

.sire-handwritten-2 {
    position: absolute;
    top: 90px;
    left: 25%;

    font-size: 35px;

    animation: leafFloat 3s ease-in-out infinite;
}


/* =========================
   TEXT FLOATING
========================= */

@keyframes textFloat {

    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-8px);
    }

    100% {
        transform: translateY(0);
    }
}


/* =========================
   LEAF FLOATING
========================= */

@keyframes leafFloat {

    0% {
        transform: translateY(0) rotate(-5deg);
    }

    50% {
        transform: translateY(-12px) rotate(8deg);
    }

    100% {
        transform: translateY(0) rotate(-5deg);
    }
}


/* =========================
   BURGER ENTER ANIMATION
========================= */

@keyframes burgerEnter {

    from {
        opacity: 0;
        transform: translateX(100px) scale(0.8);
    }

    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 991px) {

    .sire-hero-visual {
        height: 500px;
    }

    .sire-hero-plate {
        width: 420px;
        height: 420px;
    }

    .sire-handwritten-1 {
        right: 0;
        bottom: 30px;
        font-size: 25px;
    }
}


@media (max-width: 576px) {

    .sire-hero-visual {
        height: 400px;
    }

    .sire-hero-plate {
        width: 320px;
        height: 320px;
    }

    .sire-handwritten-1 {
        font-size: 20px;
        bottom: 10px;
    }

    .sire-handwritten-2 {
        font-size: 25px;
    }
}

</style>
<div class="col-lg-6 d-none d-lg-block">
    <div class="sire-hero-visual">

        <div class="sire-hero-plate">
            <img 
                src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80"
                alt="Sire signature burger"
            />
        </div>

        <span class="sire-handwritten sire-handwritten-1">
            Good Food<br>
            <em>Good Mood</em>
        </span>

        <span class="sire-handwritten sire-handwritten-2">
            🌿
        </span>

    </div>
</div>
        </div>
    </div>
</section>

<!-- FEATURE BAR -->
<section class="sire-features">
    <div class="container">
        <div class="row g-3 sire-feature-grid">
            <div class="col-6 col-md-3">
                <div class="sire-feature">
                    <span class="sire-feature-icon"><i class="bi bi-flower2"></i></span>
                    <div>
                        <h6>Fresh Ingredients</h6>
                        <small>100% natural &amp; healthy</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="sire-feature">
                    <span class="sire-feature-icon"><i class="bi bi-truck"></i></span>
                    <div>
                        <h6>Fast Delivery</h6>
                        <small>Quick and safe</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="sire-feature">
                    <span class="sire-feature-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h6>Safe Payment</h6>
                        <small>Secure transaction</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="sire-feature">
                    <span class="sire-feature-icon"><i class="bi bi-award"></i></span>
                    <div>
                        <h6>Best Quality</h6>
                        <small>Premium taste</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MENU -->
<section class="sire-section" id="sire-menu">
    <span class="sire-leaf sire-leaf-section"><i class="bi bi-flower1"></i></span>
    <div class="container">
        <div class="sire-section-head">
            <div>
                <span class="sire-eyebrow">Our Menu</span>
                <h2 class="sire-section-title">Customer <em>Feedcaume</em></h2>
                <p class="sire-section-sub">Explore our most popular dishes, made with fresh ingredients and authentic flavors.</p>
            </div>
            <a href="{{ route('foods.index') }}" class="sire-btn sire-btn-outline">View All Menu <i class="bi bi-arrow-right ms-2"></i></a>
        </div>

        <div class="row g-4">
            @forelse($categoriesList->take(2) as $category)
                <div class="col-md-6">
                    <div class="sire-menu-card">
                        <div class="sire-menu-image">
                            @if(isset($category->image) && $category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" />
                            @else
                                <img src="https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=800&q=80" alt="{{ $category->name }}" />
                            @endif
                            <span class="sire-menu-price">${{ number_format(($featuredFoods->first()->price ?? 0) + 1.5, 2) }}</span>
                        </div>
                        <div class="sire-menu-body">
                            <h3 class="sire-menu-title">{{ $category->name }}</h3>
                            <p class="sire-menu-text">A signature {{ strtolower($category->name) }} plate prepared with seasonal produce and a chef's touch.</p>
                            <a href="{{ route('categories.show', $category->id) }}" class="sire-btn sire-btn-primary">Order Now <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="sire-empty">
                        <i class="bi bi-egg-fried"></i>
                        <p>Add categories in the admin panel to populate this section.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- SPECIAL DISHES -->
<section class="sire-section sire-section-alt">
    <span class="sire-leaf sire-leaf-section"><i class="bi bi-tree-fill"></i></span>
    <div class="container">
        <div class="text-center mb-5">
            <span class="sire-eyebrow">Team's Chorsa</span>
            <h2 class="sire-section-title">Special <em>Dishes</em></h2>
            <p class="sire-section-sub mx-auto" style="max-width: 540px;">Our chef's recommendation for you.</p>
        </div>

        @if($specialFoods->count() > 0)
            <div class="row g-4">
                @foreach($specialFoods as $food)
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="sire-dish-card">
                            <a href="{{ route('foods.show', $food->id) }}" class="sire-dish-image d-block text-decoration-none">
                                <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $food->name }}" />
                                <span class="sire-dish-price">${{ number_format($food->price, 2) }}</span>
                            </a>
                            <div class="sire-dish-body">
                                <small class="text-muted mb-1 d-block">{{ $food->category->name ?? 'General' }}</small>
                                <h5 class="sire-dish-title">{{ $food->name }}</h5>
                                <p class="sire-dish-text">{{ \Illuminate\Support\Str::limit($food->description, 60) }}</p>
                                <div class="mb-2">
                                    @php
                                        $rating = $food->reviews_avg_rating ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = $rating - $fullStars >= 0.5;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="bi bi-star-fill text-warning small"></i>
                                        @elseif($i == $fullStars + 1 && $halfStar)
                                            <i class="bi bi-star-half text-warning small"></i>
                                        @else
                                            <i class="bi bi-star text-muted small"></i>
                                        @endif
                                    @endfor
                                </div>
                                @if(auth()->check() && auth()->user()->role === 'customer')
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="food_id" value="{{ $food->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="sire-btn sire-btn-primary sire-btn-block">
                                            <i class="bi bi-bag-plus me-2"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="sire-btn sire-btn-primary sire-btn-block">
                                        <i class="bi bi-box me-2"></i> Login to Order
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="sire-empty">
                <i class="bi bi-egg-fried"></i>
                <p>No special dishes available at the moment. Please check back later.</p>
            </div>
        @endif
    </div>
</section>

<!-- ABOUT -->
<section class="sire-about">
    <span class="sire-leaf sire-leaf-about-1"><i class="bi bi-flower2"></i></span>
    <span class="sire-leaf sire-leaf-about-2"><i class="bi bi-tree-fill"></i></span>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="sire-about-visual">
                    <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=1000&q=80" alt="Fresh plate" />
                    <span class="sire-handwritten sire-handwritten-3">Healthy Food<br><em>Healthy Life</em></span>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="sire-eyebrow">About Us</span>
                <h2 class="sire-section-title">Heprauts <em>Learn</em></h2>
                <p class="sire-about-text">We are a restaurant dedicated to providing fresh, healthy and delicious food. Our mission is to bring the best dining experience with high-quality ingredients, excellent service and a cozy atmosphere.</p>
                <p class="sire-about-text">From farm to fork, every dish is prepared daily by chefs who care about flavor and craft.</p>
                <a href="{{ route('about') }}" class="sire-btn sire-btn-primary sire-btn-lg">Read More <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection