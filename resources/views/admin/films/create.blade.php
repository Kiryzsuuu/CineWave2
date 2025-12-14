@extends('layouts.app')

@section('title', 'Add New Film - Admin')

@push('styles')
<style>
    .admin-container {
        max-width: 900px;
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--netflix-red);
    }

    .form-group select option {
        background: #333;
        color: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.05);
        padding: 8px 12px;
        border-radius: 6px;
    }

    .checkbox-item input[type="checkbox"] {
        width: auto;
        cursor: pointer;
    }

    .checkbox-item label {
        margin: 0;
        cursor: pointer;
    }

    .badge-checkboxes {
        display: flex;
        gap: 15px;
        margin-top: 10px;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Add New Film</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
            <a href="{{ route('admin.films.index') }}">Films</a> / 
            Add New
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Film Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="year">Release Year *</label>
                    <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 5 }}" required>
                    @error('year')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes) *</label>
                    <input type="number" id="duration" name="duration" value="{{ old('duration') }}" min="1" required>
                    @error('duration')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="rating">Rating (0-10)</label>
                    <input type="number" id="rating" name="rating" value="{{ old('rating', '7.0') }}" min="0" max="10" step="0.1">
                    @error('rating')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="age_rating">Age Rating</label>
                    <select id="age_rating" name="age_rating">
                        <option value="SU" {{ old('age_rating') == 'SU' ? 'selected' : '' }}>SU - Semua Umur</option>
                        <option value="R13+" {{ old('age_rating') == 'R13+' ? 'selected' : '' }}>R13+ - Remaja 13+</option>
                        <option value="R17+" {{ old('age_rating') == 'R17+' ? 'selected' : '' }}>R17+ - Remaja 17+</option>
                        <option value="D18+" {{ old('age_rating') == 'D18+' ? 'selected' : '' }}>D18+ - Dewasa 18+</option>
                    </select>
                    @error('age_rating')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Categories / Genres *</label>
                <div class="checkbox-group">
                    @foreach($categories as $category)
                        <div class="checkbox-item">
                            <input type="checkbox" id="genre_{{ $category->id }}" name="genre[]" value="{{ $category->id }}" 
                                {{ is_array(old('genre')) && in_array($category->id, old('genre')) ? 'checked' : '' }}>
                            <label for="genre_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('genre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="trailer_url">Trailer URL (YouTube)</label>
                <input type="url" id="trailer_url" name="trailer_url" value="{{ old('trailer_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                @error('trailer_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="video_url">Video URL *</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" required placeholder="https://example.com/video.mp4">
                <small>Direct link to the video file</small>
                @error('video_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="poster_url">Poster URL *</label>
                    <input type="url" id="poster_url" name="poster_url" value="{{ old('poster_url') }}" required placeholder="https://example.com/poster.jpg">
                    @error('poster_url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="backdrop_url">Backdrop URL</label>
                    <input type="url" id="backdrop_url" name="backdrop_url" value="{{ old('backdrop_url') }}" placeholder="https://example.com/backdrop.jpg">
                    @error('backdrop_url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Badges</label>
                <div class="badge-checkboxes">
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured"><i class="fas fa-star"></i> Featured</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_trending" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
                        <label for="is_trending"><i class="fas fa-fire"></i> Trending</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                        <label for="is_new"><i class="fas fa-certificate"></i> New Release</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Create Film
                </button>
                <a href="{{ route('admin.films.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
