<section class="hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">
                    <i class="bi bi-lightning-charge-fill me-1"></i> FAST DELIVERY IN 30 MIN
                </span>
                <h1 class="hero-title">Delicious Food,<br>Delivered Fresh</h1>
                <p class="hero-subtitle">Order from our restaurant and get fresh, hot food delivered straight to your doorstep. Quality ingredients, amazing taste, every single time.</p>
                <form action="{{ route('foods.index') }}" method="GET" class="hero-search">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search for food, restaurants..." value="{{ request('search') }}">
                        <button class="btn btn-primary px-4" type="submit">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div class="hero-icon">
                    <i class="bi bi-basket2-fill"></i>
                </div>
            </div>
        </div>
    </div>
</section>
