@extends('layouts.app')

@section('title', 'My Profile - CineWave')

@push('styles')
<style>
    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .profile-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 40px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .avatar-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: white;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .form-section {
        margin-bottom: 40px;
    }

    .form-section h2 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }

    .form-group input {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--netflix-red);
    }

    .form-group input[type="file"] {
        padding: 10px;
        cursor: pointer;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.4);
        color: #28a745;
    }

    .alert-error {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.4);
        color: #dc3545;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    .btn-save {
        background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
        color: white;
        padding: 12px 40px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.5);
    }

    .user-info-box {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .user-info-box p {
        margin: 8px 0;
        display: flex;
        justify-content: space-between;
    }

    .user-info-box strong {
        color: rgba(255, 255, 255, 0.7);
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-verified {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }

    .badge-admin {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 20px;
        }

        .profile-header h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1>Profil Saya</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="profile-card">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="avatar-section">
                <div class="avatar-preview">
                    @if($user->avatar_url)
                        <img src="{{ Storage::url($user->avatar_url) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="form-group">
                    <label for="avatar">Change Avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                    @error('avatar')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small style="color: rgba(255,255,255,0.6); display: block; margin-top: 5px;">
                        Max 2MB (JPEG, PNG, JPG, GIF)
                    </small>
                </div>
            </div>

            <!-- Account Info -->
            <div class="user-info-box">
                <p>
                    <strong>Status Akun:</strong>
                    <span>
                        @if($user->is_verified)
                            <span class="badge badge-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                        @else
                            <span class="badge" style="background: rgba(220, 53, 69, 0.2); color: #dc3545;">
                                <i class="fas fa-times-circle"></i> Belum Terverifikasi
                            </span>
                        @endif
                        @if($user->is_admin)
                            <span class="badge badge-admin"><i class="fas fa-crown"></i> Admin</span>
                        @endif
                    </span>
                </p>
                <p>
                    <strong>Bergabung sejak:</strong>
                    <span>{{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}</span>
                </p>
            </div>

            <!-- Personal Information -->
            <div class="form-section">
                <h2><i class="fas fa-user"></i> Informasi Pribadi</h2>
                
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Change Password -->
            <div class="form-section">
                <h2><i class="fas fa-lock"></i> Ubah Password</h2>
                
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter">
                    @error('new_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ketik ulang password baru">
                </div>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
