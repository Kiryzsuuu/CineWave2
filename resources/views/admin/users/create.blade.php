@extends('layouts.app')

@section('title', 'Add New User - Admin')

@push('styles')
<style>
    .admin-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .admin-header {
        margin-bottom: 30px;
    }

    .admin-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .breadcrumb {
        color: #999;
        font-size: 14px;
    }

    .breadcrumb a {
        color: var(--netflix-red);
        text-decoration: none;
    }

    .form-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 40px;
        backdrop-filter: blur(10px);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--netflix-red);
    }

    .form-group select option {
        background: #333;
        color: white;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
        cursor: pointer;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.5);
    }

    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding: 12px 30px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    small {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Add New User</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
            <a href="{{ route('admin.users.index') }}">Users</a> / 
            Add New
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+62 812-3456-7890">
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
                <small>Minimum 8 characters</small>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                    <label for="is_verified" style="margin: 0;">Email Verified</label>
                </div>
                <small>Mark this user as verified (skip OTP verification)</small>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                    <label for="is_admin" style="margin: 0;">Admin User</label>
                </div>
                <small>Grant admin privileges to this user</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
