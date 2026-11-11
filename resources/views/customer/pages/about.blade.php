@extends('layouts.customer')

@section('title', 'About Us')

@section('content')
<section class="sire-about">
    <span class="sire-leaf sire-leaf-about-1"><i class="bi bi-flower2"></i></span>
    <span class="sire-leaf sire-leaf-about-2"><i class="bi bi-tree-fill"></i></span>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="sire-about-visual">
                    <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=1000&q=80" alt="Sire kitchen" />
                    <span class="sire-handwritten sire-handwritten-3">Healthy Food<br><em>Healthy Life</em></span>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="sire-eyebrow">About Us</span>
                <h1 class="sire-section-title">Heprauts <em>Learn</em></h1>
                <p class="sire-about-text">We are a restaurant dedicated to providing fresh, healthy and delicious food. Our mission is to bring the best dining experience with high-quality ingredients, excellent service and a cozy atmosphere.</p>
                <p class="sire-about-text">From farm to fork, every dish is prepared daily by chefs who care about flavor and craft.</p>
                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div class="sire-feature h-100">
                            <span class="sire-feature-icon"><i class="bi bi-flower2"></i></span>
                            <div>
                                <h6>Fresh</h6>
                                <small>Local ingredients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sire-feature h-100">
                            <span class="sire-feature-icon"><i class="bi bi-truck"></i></span>
                            <div>
                                <h6>Fast</h6>
                                <small>Quick delivery</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sire-feature h-100">
                            <span class="sire-feature-icon"><i class="bi bi-award"></i></span>
                            <div>
                                <h6>Quality</h6>
                                <small>Premium taste</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sire-feature h-100">
                            <span class="sire-feature-icon"><i class="bi bi-heart"></i></span>
                            <div>
                                <h6>Care</h6>
                                <small>Made with love</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4 g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: #fff;">
                    <div class="card-body p-4">
                        <span class="sire-feature-icon mb-3"><i class="bi bi-bookmark-heart"></i></span>
                        <h5 class="fw-bold mb-2" style="color: var(--primary-dark);">Our Story</h5>
                        <p class="text-muted mb-0">Founded with a passion for healthy, fresh food and a love for our community.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: #fff;">
                    <div class="card-body p-4">
                        <span class="sire-feature-icon mb-3"><i class="bi bi-bullseye"></i></span>
                        <h5 class="fw-bold mb-2" style="color: var(--primary-dark);">Our Mission</h5>
                        <p class="text-muted mb-0">To make fresh, healthy food accessible, delicious, and delivered with care.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: #fff;">
                    <div class="card-body p-4">
                        <span class="sire-feature-icon mb-3"><i class="bi bi-stars"></i></span>
                        <h5 class="fw-bold mb-2" style="color: var(--primary-dark);">What We Offer</h5>
                        <p class="text-muted mb-0">A wide menu, real-time tracking, secure payments, and dedicated delivery.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection