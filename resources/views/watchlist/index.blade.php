@extends('layouts.app')

@section('title', 'My Watchlist - CineWave')

@push('styles')
<style>
    .watchlist-container {
        padding: 40px 4%;
        min-height: calc(100vh - 140px);
    }

    .watchlist-header {
        margin-bottom: 40px;
    }

    .watchlist-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .watchlist-count {
        color: #999;
        font-size: 16px;
    }

    .films-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .film-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .film-card:hover {
        transform: scale(1.05);
        z-index: 10;
    }

    .film-poster {
        width: 100%;
        aspect-ratio: 2/3;
        object-fit: cover;
    }

    .film-info {
        padding: 15px;
    }

    .film-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .film-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #999;
    }

    .film-rating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 5;
    }

    .film-card:hover .remove-btn {
        opacity: 1;
    }

    .remove-btn:hover {
        background: var(--netflix-red);
        transform: scale(1.1);
    }

    .empty-state {
        text-align: center;
        padding: 100px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h2 {
        font-size: 28px;
        margin-bottom: 15px;
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 30px;
    }

    .btn-browse {
        display: inline-block;
        background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-browse:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.5);
    }

    @media (max-width: 768px) {
        .films-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .watchlist-header h1 {
            font-size: 28px;
        }
    }

    @media (max-width: 480px) {
        .films-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="watchlist-container">
    <div class="watchlist-header">
        <h1><i class="fas fa-heart"></i> My Watchlist</h1>
        <p class="watchlist-count">{{ $films->count() }} film{{ $films->count() != 1 ? 's' : '' }} in your watchlist</p>
    </div>

    @if($films->count() > 0)
        <div class="films-grid">
            @foreach($films as $film)
                <div class="film-card" onclick="window.location='{{ route('film.show', $film->slug) }}'">
                    <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="film-poster">
                    
                    <form action="{{ route('watchlist.toggle', $film->id) }}" method="POST" style="display: inline;" onclick="event.stopPropagation();">
                        @csrf
                        <button type="submit" class="remove-btn" title="Remove from watchlist">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    
                    <div class="film-info">
                        <div class="film-title">{{ $film->title }}</div>
                        <div class="film-meta">
                            <span class="film-rating">
                                <i class="fas fa-star" style="color: #ffd700;"></i>
                                {{ number_format($film->rating, 1) }}
                            </span>
                            <span>{{ $film->year }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-film" style="opacity: 0.3; font-size: 80px; margin-bottom: 20px;"></i>
            <h2>Your Watchlist is Empty</h2>
            <p>Start adding films to your watchlist to watch them later!</p>
            <a href="{{ route('browse') }}" class="btn-browse">
                <i class="fas fa-search"></i> Browse Films
            </a>
        </div>
    @endif
</div>
@endsection
