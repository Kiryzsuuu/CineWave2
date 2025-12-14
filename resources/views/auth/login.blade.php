@extends('layouts.app')

@section('title', 'Masuk - CineWave')

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .auth-box {
        background: rgba(0,0,0,0.75);
        border-radius: 4px;
        padding: 60px 68px 40px;
        max-width: 450px;
        width: 100%;
    }

    .auth-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 28px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-control {
        width: 100%;
        padding: 16px 20px;
        background: #333;
        border: none;
        border-radius: 4px;
        color: #fff;
        font-size: 16px;
    }

    .form-control:focus {
        outline: none;
        background: #454545;
    }

    .form-control::placeholder {
        color: #8c8c8c;
    }

    .form-error {
        color: #e87c03;
        font-size: 13px;
        margin-top: 6px;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: var(--netflix-red);
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 24px;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: #f40612;
    }

    .form-help {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 13px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #b3b3b3;
    }

    .remember-me input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .form-help a {
        color: #b3b3b3;
        text-decoration: none;
    }

    .form-help a:hover {
        text-decoration: underline;
    }

    .signup-now {
        margin-top: 16px;
        color: #737373;
    }

    .signup-now a {
        color: #fff;
        text-decoration: none;
    }

    .signup-now a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .auth-box {
            padding: 40px 28px;
        }

        .auth-title {
            font-size: 26px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <h1 class="auth-title">Masuk</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Masuk</button>

            <div class="form-help">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
                <a href="#">Butuh bantuan?</a>
            </div>
        </form>

        <div class="signup-now">
            Baru di CineWave? <a href="{{ route('register') }}">Daftar sekarang</a>.
        </div>
    </div>
</div>
@endsection
