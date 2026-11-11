@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<style>
    :root {
        --bg-green: #7fc079;
        --paper: #eef3e6;
        --sage-card: #cfe0bb;
        --forest: #1f4d2c;
        --leaf: #3f8f52;
        --ink: #1c2b1f;
        --white: #ffffff;
        --danger: #c0392b;
    }

    .reset-page {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100dvh;
        background: var(--bg-green);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 36px;
        overflow: auto;
    }

    .reset-card {
        width: min(400px, 92%);
        background: var(--white);
        border-radius: 26px;
        padding: 40px 32px;
        box-shadow: 0 40px 90px -20px rgba(15, 40, 20, 0.45);
    }

    .auth-title {
        margin: 0 0 8px;
        color: var(--forest);
        font-family: 'Fraunces', serif;
        font-size: 24px;
        font-weight: 600;
    }

    .auth-subtitle {
        margin: 0 0 24px;
        color: #3f5a41;
        font-size: 13px;
        line-height: 1.55;
    }

    .form-field { margin-bottom: 16px; }

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

    .input-error { border-color: var(--danger) !important; animation: fieldShake 0.4s ease; }

    .error-message {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        color: var(--danger);
        font-size: 11px;
    }

    .send-btn {
        width: 100%;
        height: 44px;
        border: 0;
        border-radius: 999px;
        background: var(--forest);
        color: var(--white);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .send-btn:hover { background: var(--leaf); transform: translateY(-2px); }

    .back-link {
        text-align: center;
        margin-top: 16px;
        font-size: 12px;
        color: #3f5a41;
    }

    .back-link a { color: var(--forest); font-weight: 700; text-decoration: none; }

    .back-link a:hover { text-decoration: underline; }

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

    @keyframes fieldShake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(5px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(3px); }
    }
</style>

<div class="reset-page">
    <div class="reset-card">
        <div class="login-brand">
            <span class="leaf-mark">🌿</span>
            <span>FoodDelivery</span>
        </div>

        <h2 class="auth-title">Reset Password</h2>
        <p class="auth-subtitle">
            Create a new password for your account.
        </p>

        @if(session('success'))
            <div class="alert alert-success custom-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger custom-alert" style="background:#fbeae9; color: var(--danger);">
                <i class="bi bi-exclamation-circle"></i>
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-field">
                <label for="email">E-mail</label>
                <div class="modern-input @error('email') input-error @enderror">
                    <input
                        type="email"
                        id="email"
                        name="email"
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

            <div class="form-field">
                <label for="password">New Password</label>
                <div class="modern-input @error('password') input-error @enderror">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="New password"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('password')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="modern-input @error('password_confirmation') input-error @enderror">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm new password"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('password_confirmation')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="send-btn">Reset Password</button>
        </form>

        <p class="back-link">
            Remember your password? <a href="{{ route('login') }}">Log in</a>
        </p>
    </div>
</div>
@endsection