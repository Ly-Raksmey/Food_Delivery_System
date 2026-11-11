<nav class="sire-navbar">
    <div class="sire-nav-inner">
        <a class="sire-brand" href="{{ route('home') }}">
            <span class="sire-brand-mark">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8 6 5 9 5 13a7 7 0 0 0 14 0c0-4-3-7-7-11Z" fill="currentColor"/>
                    <path d="M12 13c-1.5-2-3-3-3-5a3 3 0 0 1 6 0c0 2-1.5 3-3 5Z" fill="rgba(255,255,255,0.4)"/>
                </svg>
            </span>
            <span class="sire-brand-text">FoodDelivery</span>
        </a>

        {{-- <button class="sire-toggler" type="button" onclick="document.getElementById('sireNav').classList.toggle('open')" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button> --}}

        <div class="sire-collapse show" id="sireNav">
            <ul class="sire-nav-links">
                <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li><a class="{{ request()->routeIs('foods.*') || request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('foods.index') }}">Menu</a></li>
                <li><a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                <li><a class="{{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
                <li><a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            <div class="sire-nav-actions">
                <button class="sire-icon-btn" type="button" title="Search" onclick="document.getElementById('sireSearch')?.classList.toggle('open')">
                    <i class="bi bi-search"></i>
                </button>
                <a class="sire-icon-btn sire-cart-btn" href="{{ route('cart.index') }}" title="Cart">
                    <i class="bi bi-bag"></i>
                    @php
                        $cartCount = auth()->check() && auth()->user()->cart ? auth()->user()->cart->items->sum('quantity') : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="sire-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <div class="sire-user-menu">
                        <button class="sire-user-btn" type="button" data-bs-toggle="dropdown">
                            <span class="sire-avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu sire-dropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-box me-2"></i> My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-2"></i> Notifications</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">@csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="sire-btn sire-btn-outline" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>

    <div id="sireSearch" class="sire-search-bar">
        <div class="container">
            <form action="{{ route('foods.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                <i class="bi bi-search text-muted"></i>
                <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="Search dishes, ingredients..." value="{{ request('search') }}" autofocus>
                <button class="sire-btn sire-btn-primary btn-sm" type="submit">Search</button>
                <button type="button" class="sire-icon-btn" onclick="document.getElementById('sireSearch').classList.remove('open')"><i class="bi bi-x-lg"></i></button>
            </form>
        </div>
    </div>
</nav>