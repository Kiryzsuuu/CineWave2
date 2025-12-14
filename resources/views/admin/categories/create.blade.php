@extends('layouts.app')

@section('title', 'Add New Category - Admin')

@push('styles')
<style>
    .admin-container {
        max-width: 700px;
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
        display: block;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Add New Category</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
            <a href="{{ route('admin.categories.index') }}">Categories</a> / 
            Add New
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Category Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug *</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
                <small>URL-friendly name (lowercase, no spaces, use hyphens)</small>
                @error('slug')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="order">Display Order *</label>
                <input type="number" id="order" name="order" value="{{ old('order', '0') }}" min="0" required>
                <small>Lower numbers appear first</small>
                @error('order')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" style="margin: 0;">Active</label>
                </div>
                <small>Only active categories will be shown on the website</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    });
</script>
@endsection
