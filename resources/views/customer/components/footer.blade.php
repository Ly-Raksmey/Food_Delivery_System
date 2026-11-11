<footer class="sire-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a class="sire-brand sire-brand-light mb-3" href="{{ route('home') }}">
                    <span class="sire-brand-mark">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8 6 5 9 5 13a7 7 0 0 0 14 0c0-4-3-7-7-11Z" fill="currentColor"/>
                            <path d="M12 13c-1.5-2-3-3-3-5a3 3 0 0 1 6 0c0 2-1.5 3-3 5Z" fill="rgba(255,255,255,0.4)"/>
                        </svg>
                    </span>
                    <span class="sire-brand-text">Sire</span>
                </a>
                <p class="sire-footer-tag">Fresh Food • Healthy Life</p>
                <p class="sire-footer-text">A modern restaurant dedicated to bringing fresh, healthy, and delicious dishes to your table — crafted with care from locally sourced ingredients.</p>
            </div>

            <div class="col-6 col-md-3 col-lg-2 offset-lg-1">
                <h6 class="sire-footer-title">Explore</h6>
                <ul class="sire-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('foods.index') }}">Menu</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <h6 class="sire-footer-title">Account</h6>
                <ul class="sire-footer-links">
                    @auth
                        <li><a href="{{ route('profile.index') }}">Profile</a></li>
                        <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>

            <div class="col-md-6 col-lg-3">
                <h6 class="sire-footer-title">Contact</h6>
                <ul class="sire-footer-contact">
                    <li><i class="bi bi-geo-alt"></i> Phnom Penh, Cambodia</li>
                    <li><i class="bi bi-telephone"></i> +855 967878753</li>
                    <li><i class="bi bi-envelope"></i> makarakan117@gmail.com</li>
                </ul>
                <div class="sire-socials mt-3">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="sire-footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} FoodDelivery Restaurant. All rights reserved.</p>
        </div>
    </div>
</footer>