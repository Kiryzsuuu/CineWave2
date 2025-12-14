@extends('layouts.app')

@section('title', 'Verifikasi OTP - CineWave')

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
        text-align: center;
    }

    .auth-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .auth-description {
        color: #b3b3b3;
        margin-bottom: 28px;
        font-size: 14px;
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
        font-size: 24px;
        text-align: center;
        letter-spacing: 8px;
    }

    .form-control:focus {
        outline: none;
        background: #454545;
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

    .resend-link {
        margin-top: 16px;
        color: #737373;
        font-size: 14px;
    }

    .resend-link button {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
        font-size: 14px;
    }

    .resend-link button:hover {
        color: #b3b3b3;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <h1 class="auth-title">Verifikasi Email</h1>
        <p class="auth-description">
            Kami telah mengirimkan kode OTP ke email <strong>{{ $email }}</strong>
        </p>
        
        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <input type="text" name="otp" class="form-control" placeholder="000000" maxlength="6" pattern="\d{6}" required autofocus>
                @error('otp')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Verifikasi</button>
        </form>

        <div class="resend-link">
            Tidak menerima kode? 
            <form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit">Kirim ulang</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto submit when 6 digits entered
    document.querySelector('input[name="otp"]').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
</script>
@endpush
