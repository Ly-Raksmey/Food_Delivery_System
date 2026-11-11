@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --bg-green: #7fc079;
        --paper: #eef3e6;
        --sage-card: #cfe0bb;
        --sage-card-deep: #b9d29e;
        --forest: #1f4d2c;
        --leaf: #3f8f52;
        --ink: #1c2b1f;
        --muted: #6b7a6c;
        --white: #ffffff;
        --danger: #c0392b;
    }

    * { box-sizing: border-box; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    html, body { height: 100%; margin: 0; padding: 0; }

    body {
        color: var(--ink);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Full-bleed backdrop, escapes the auth layout's own container/padding */
    .login-page {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        height: 100dvh;
        background: var(--bg-green);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 36px;
        overflow: auto;
        z-index: 1;
    }

    .login-shell {
        width: min(1160px, 100%);
        height: min(720px, 100%);
        background: var(--white);
        border-radius: 26px;
        box-shadow: 0 40px 90px -20px rgba(15, 40, 20, 0.45);
        display: flex;
        overflow: hidden;
        opacity: 0;
        animation: riseIn 0.6s cubic-bezier(.2,.8,.2,1) 0.05s forwards;
    }

    /* ---------- LEFT PANEL ---------- */
    .shell-left {
        flex: 1 1 50%;
        background: var(--paper);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 38px 52px;
        min-width: 0;
    }

    .login-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--forest);
        font-family: 'Fraunces', serif;
        font-size: 22px;
        font-weight: 600;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .login-brand .leaf-mark { font-size: 15px; transform: rotate(-10deg); }

    .login-brand span {
        border-bottom: 2.5px solid var(--leaf);
        padding-bottom: 2px;
    }

    .left-center {
        flex: 1;
        display: flex;
        align-items: center;
        min-height: 0;
        padding: 8px 0;
    }

    .login-card {
        width: 100%;
        max-width: 380px;
        background: var(--sage-card);
        border-radius: 20px;
        padding: 30px 28px;
        box-shadow: 0 18px 34px -14px rgba(31, 77, 44, 0.35);
    }

    .auth-title {
        margin: 0 0 8px;
        color: var(--forest);
        font-family: 'Fraunces', serif;
        font-size: 24px;
        font-weight: 600;
    }

    .auth-subtitle {
        margin: 0 0 22px;
        color: #3f5a41;
        font-size: 12.5px;
        line-height: 1.55;
    }

    .form-field { margin-bottom: 14px; }

    .form-field label {
        display: block;
        margin-bottom: 6px;
        color: var(--forest);
        font-size: 12px;
        font-weight: 700;
    }

    .modern-input {
        height: 42px;
        display: flex;
        align-items: center;
        background: var(--white);
        border: 1.5px solid transparent;
        border-radius: 10px;
        padding: 0 13px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-input:focus-within {
        border-color: var(--leaf);
        box-shadow: 0 0 0 3px rgba(63, 143, 82, 0.18);
    }

    .modern-input input {
        width: 100%;
        height: 100%;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--ink);
        font-size: 13px;
        font-family: 'Inter', sans-serif;
    }

    .modern-input input::placeholder { color: #9aac97; }

    .password-toggle {
        border: 0;
        background: transparent;
        color: #7c8f79;
        padding: 4px;
        cursor: pointer;
        font-size: 15px;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .password-toggle:hover { color: var(--forest); }
    .password-toggle.is-visible { transform: rotate(-8deg); }

    .input-error { border-color: var(--danger) !important; animation: fieldShake 0.4s ease; }

    .error-message {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        color: var(--danger);
        font-size: 11px;
    }

    .forgot-link {
        display: block;
        text-align: right;
        margin: 6px 0 20px;
        color: #4c6b4a;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .forgot-link:hover { color: var(--forest); text-decoration: underline; }

    .login-btn {
        width: 100%;
        height: 44px;
        border: 0;
        border-radius: 999px;
        background: var(--forest);
        color: var(--white);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 14px 24px -10px rgba(31, 77, 44, 0.55);
        transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }

    .login-btn:hover { background: var(--leaf); transform: translateY(-2px); }
    .login-btn:active { transform: translateY(0); }

    .card-footer-link {
        text-align: center;
        margin-top: 16px;
        font-size: 12px;
        color: #3f5a41;
    }

    .card-footer-link a {
        color: var(--forest);
        font-weight: 700;
        text-decoration: none;
    }

    .card-footer-link a:hover { text-decoration: underline; }

    .custom-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 0;
        border-radius: 10px;
        background: #dff3e2;
        color: var(--forest);
        font-size: 12px;
        padding: 10px 14px;
        margin-bottom: 14px;
    }

    .custom-alert .btn-close { margin-left: auto; }

    .split-footer {
        border-top: 1px solid var(--sage-card-deep);
        padding-top: 12px;
        color: var(--muted);
        font-size: 12px;
        flex-shrink: 0;
    }

    /* ---------- RIGHT PANEL ---------- */
    .shell-right {
        flex: 1 1 50%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #a9d59a;
    }

    .produce-pattern {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .right-overlay {
        position: relative;
        z-index: 2;
        width: 240px;
        text-align: center;
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(6px);
        border-radius: 20px;
        padding: 30px 26px 26px;
        box-shadow: 0 22px 45px -16px rgba(15, 40, 20, 0.4);
        opacity: 0;
        animation: riseIn 0.6s cubic-bezier(.2,.8,.2,1) 0.25s forwards;
    }

    .right-overlay .login-brand {
        justify-content: center;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .right-overlay p {
        margin: 0;
        color: #3f5a41;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
    }

    .accent-arrows {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 14px;
    }

    .accent-arrows span {
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-left: 9px solid var(--leaf);
    }

    .accent-arrows span:nth-child(2) { border-left-color: #e2493d; }
    .accent-arrows span:nth-child(3) { border-left-color: #e8954a; }

    /* ---------- ANIMATIONS ---------- */
    @keyframes riseIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fieldShake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(5px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(3px); }
    }

    /* ---------- MOBILE ---------- */
    @media (max-width: 900px) {
        .login-page { padding: 0; }
        .login-shell { flex-direction: column; height: auto; min-height: 100%; border-radius: 0; }
        .shell-right { min-height: 260px; padding: 32px 0; }
        .shell-left { padding: 30px 24px; }
        .right-overlay { width: 200px; }
    }
</style>

<div class="login-page">
    <div class="login-shell">

        <!-- LEFT: form panel -->
        <div class="shell-left">
            <div class="login-brand">
                <span class="leaf-mark">🌿</span>
                <span>FoodDelivery</span>
            </div>

            <div class="left-center">
                <div class="login-card">
                    <h2 class="auth-title">Log in</h2>
                    <p class="auth-subtitle">
                        Welcome to the FoodDelivery login dashboard, please log in to use the following app.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success custom-alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="form-field">
                            <label for="email">E-mail</label>
                            <div class="modern-input @error('email') input-error @enderror">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your e-mail"
                                    required
                                    autofocus
                                    autocomplete="email"
                                >
                            </div>
                            @error('email')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-field">
                            <label for="password">Password</label>
                            <div class="modern-input @error('password') input-error @enderror">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>

                        <button type="submit" class="login-btn">Login</button>
                    </form>

                    <div class="google-divider" style="text-align:center; margin: 18px 0 10px; position:relative;">
                        <span style="position:relative; z-index:1; background:var(--white); padding:0 10px; font-size:11px; color:var(--muted); font-weight:700;">Or continue with</span>
                    </div>

                    <a href="{{ route('google.login') }}" class="google-btn" style="width:100%; height:44px; border:1.5px solid #dce2dd; border-radius:999px; background:var(--white); color:var(--ink); font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:10px; cursor:pointer; transition:background 0.2s ease, border-color 0.2s ease;">
                        <img src="https://www.google.com/favicon.ico" alt="Google" width="18" height="18" style="display:block;">
                        Sign in with Google
                    </a>

                    <p class="card-footer-link">
                        Don't have an account? <a href="{{ route('register') }}">Register</a>
                    </p>
                </div>
            </div>

            <div class="split-footer">
                &copy; {{ date('Y') }} FoodDelivery
            </div>
        </div>

        <!-- RIGHT: illustrated panel -->
        <div class="shell-right">
            <svg class="produce-pattern" viewBox="0 0 420 560" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <rect width="420" height="560" fill="#a9d59a"/>

                <defs>
                    <g id="p-watermelon">
                        <path d="M0 0 A30 30 0 0 1 30 30 L0 30 Z" fill="#e2493d" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M0 0 A30 30 0 0 1 30 30" fill="none" stroke="#3f8f52" stroke-width="4"/>
                        <circle cx="10" cy="20" r="1.4" fill="#1f4d2c"/>
                        <circle cx="17" cy="14" r="1.4" fill="#1f4d2c"/>
                    </g>
                    <g id="p-corn">
                        <ellipse cx="12" cy="20" rx="10" ry="18" fill="#f4d35e" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M4 6 Q-4 -6 6 -10" fill="none" stroke="#3f8f52" stroke-width="4" stroke-linecap="round"/>
                        <path d="M18 6 Q26 -6 16 -12" fill="none" stroke="#3f8f52" stroke-width="4" stroke-linecap="round"/>
                        <path d="M4 10 h16 M4 16 h16 M4 22 h16 M4 28 h16" stroke="#1f4d2c" stroke-width="1.4"/>
                    </g>
                    <g id="p-mushroom">
                        <path d="M0 14 Q0 -4 16 -4 Q32 -4 32 14 Z" fill="#e8954a" stroke="#1f4d2c" stroke-width="2.5"/>
                        <circle cx="10" cy="6" r="1.6" fill="#fff"/>
                        <circle cx="20" cy="4" r="1.6" fill="#fff"/>
                        <circle cx="24" cy="10" r="1.6" fill="#fff"/>
                        <rect x="11" y="14" width="10" height="14" rx="4" fill="#fdfaf3" stroke="#1f4d2c" stroke-width="2.5"/>
                    </g>
                    <g id="p-pepper">
                        <path d="M14 0 Q30 4 26 22 Q20 34 8 30 Q-4 24 2 10 Q6 0 14 0 Z" fill="#e2493d" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M14 0 Q10 -8 18 -10" fill="none" stroke="#3f8f52" stroke-width="4" stroke-linecap="round"/>
                    </g>
                    <g id="p-pear">
                        <path d="M14 4 Q26 10 24 24 Q22 36 12 36 Q0 36 2 22 Q3 10 14 4 Z" fill="#f4d35e" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M14 4 Q12 -4 18 -8" fill="none" stroke="#3f8f52" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <g id="p-tomato">
                        <circle cx="14" cy="18" r="14" fill="#e2493d" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M14 4 l-4 -6 l4 3 l4 -3 l-4 6" fill="#3f8f52" stroke="#1f4d2c" stroke-width="1.6"/>
                    </g>
                    <g id="p-cherries">
                        <path d="M6 0 Q4 12 8 18" fill="none" stroke="#3f8f52" stroke-width="2.4"/>
                        <path d="M14 0 Q16 10 18 16" fill="none" stroke="#3f8f52" stroke-width="2.4"/>
                        <circle cx="7" cy="22" r="7.5" fill="#e2493d" stroke="#1f4d2c" stroke-width="2.2"/>
                        <circle cx="19" cy="20" r="7.5" fill="#e2493d" stroke="#1f4d2c" stroke-width="2.2"/>
                    </g>
                    <g id="p-cucumber">
                        <rect x="0" y="8" width="42" height="14" rx="7" fill="#8bc76a" stroke="#1f4d2c" stroke-width="2.5" transform="rotate(-18 21 15)"/>
                    </g>
                    <g id="p-banana">
                        <path d="M0 24 Q4 0 26 -2 Q30 0 27 4 Q10 6 6 26 Q2 30 0 24 Z" fill="#f4d35e" stroke="#1f4d2c" stroke-width="2.5"/>
                    </g>
                    <g id="p-pumpkin">
                        <circle cx="16" cy="18" r="16" fill="#e8954a" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M16 2 v32 M6 6 Q16 18 6 30 M26 6 Q16 18 26 30" fill="none" stroke="#c9711f" stroke-width="1.6"/>
                        <path d="M16 2 Q14 -6 20 -8" fill="none" stroke="#3f8f52" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <g id="p-carrot">
                        <path d="M6 0 Q16 -8 24 0" fill="none" stroke="#3f8f52" stroke-width="3" stroke-linecap="round"/>
                        <path d="M4 2 L26 2 L15 36 Z" fill="#e8954a" stroke="#1f4d2c" stroke-width="2.5"/>
                    </g>
                    <g id="p-onion">
                        <path d="M14 2 Q26 8 24 22 Q22 34 14 34 Q6 34 4 22 Q2 8 14 2 Z" fill="#fdfaf3" stroke="#1f4d2c" stroke-width="2.5"/>
                        <path d="M14 2 Q12 -6 18 -10" fill="none" stroke="#3f8f52" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <g id="p-leaf">
                        <path d="M0 20 Q0 0 20 0 Q20 20 0 20 Z" fill="#3f8f52" stroke="#1f4d2c" stroke-width="2"/>
                    </g>
                    <circle id="p-dot-y" r="3" fill="#f4d35e"/>
                    <circle id="p-dot-p" r="3" fill="#e85f8a"/>
                </defs>

                <use href="#p-watermelon" x="250" y="10"/>
                <use href="#p-corn" x="300" y="50"/>
                <use href="#p-mushroom" x="30" y="50" transform="rotate(-10 46 66)"/>
                <use href="#p-pepper" x="150" y="60" transform="rotate(12 164 74)"/>
                <use href="#p-pear" x="210" y="90"/>
                <use href="#p-tomato" x="40" y="150"/>
                <use href="#p-tomato" x="60" y="170"/>
                <use href="#p-cherries" x="180" y="200"/>
                <use href="#p-mushroom" x="20" y="240" transform="rotate(8 36 256)"/>
                <use href="#p-pumpkin" x="290" y="160"/>
                <use href="#p-cucumber" x="260" y="270" transform="rotate(6 281 277)"/>
                <use href="#p-pepper" x="330" y="240" transform="rotate(-20 344 254)"/>
                <use href="#p-banana" x="20" y="380" transform="rotate(-6 33 393)"/>
                <use href="#p-banana" x="55" y="400" transform="rotate(10 68 413)"/>
                <use href="#p-corn" x="150" y="400"/>
                <use href="#p-onion" x="330" y="400"/>
                <use href="#p-pumpkin" x="270" y="440"/>
                <use href="#p-leaf" x="120" y="20" transform="rotate(24 130 30)"/>
                <use href="#p-leaf" x="360" y="330" transform="rotate(-30 370 340)"/>

                <use href="#p-dot-y" x="120" y="130"/>
                <use href="#p-dot-p" x="220" y="140"/>
                <use href="#p-dot-y" x="80" y="230"/>
                <use href="#p-dot-p" x="200" y="330"/>
                <use href="#p-dot-y" x="310" y="330"/>
                <use href="#p-dot-p" x="120" y="380"/>
                <use href="#p-dot-y" x="240" y="60"/>
            </svg>

            <div class="right-overlay">
                <div class="login-brand">
                    <span class="leaf-mark">🌿</span>
                    <span>FoodDelivery</span>
                </div>
                <p>Good food is the foundation of a good life</p>
                <div class="accent-arrows">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');
    const toggleBtn = icon.closest('.password-toggle');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        toggleBtn.classList.add('is-visible');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        toggleBtn.classList.remove('is-visible');
    }
}
</script>

@endsection