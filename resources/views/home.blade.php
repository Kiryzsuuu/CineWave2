@extends('layouts.app')

@section('title', 'CineWave - Streaming Film Terbaik')

@push('styles')
<style>
    /* Hero Section */
    .hero {
        position: relative;
        height: 80vh;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        z-index: 0;
    }

    .hero-background::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(to bottom, rgba(20,20,20,0), rgba(20,20,20,1));
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 600px;
        padding: 0 4%;
    }

    .hero-title {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    }

    .hero-description {
        font-size: 18px;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
        line-height: 1.4;
    }

    .hero-buttons {
        display: flex;
        gap: 15px;
    }

    .hero-buttons .btn {
        padding: 12px 30px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-play {
        background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(229, 9, 20, 0.4);
        border: none;
    }

    .btn-play:hover {
        background: linear-gradient(135deg, #f40612 0%, #d00812 100%);
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(229, 9, 20, 0.6);
    }

    .btn-info {
        background: rgba(51, 51, 51, 0.8);
        color: #fff;
        font-weight: 600;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .btn-info:hover {
        background: rgba(51, 51, 51, 0.95);
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.05);
    }

    /* Row Sections */
    .row-section {
        margin-bottom: 40px;
    }

    .row-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        padding: 0 4%;
    }

    .film-row {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        gap: 8px;
        padding: 0 4%;
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .film-row::-webkit-scrollbar {
        display: none;
    }

    .film-card {
        min-width: 250px;
        height: 140px;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease;
        position: relative;
        background-size: cover;
        background-position: center;
    }

    .film-card:hover {
        transform: scale(1.05);
        z-index: 10;
    }

    .film-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60%;
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.8));
    }

    .film-card-title {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        z-index: 2;
        font-size: 14px;
        font-weight: 600;
    }

    .film-card-rating {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--netflix-gray);
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            height: 60vh;
        }

        .hero-title {
            font-size: 32px;
        }

        .hero-description {
            font-size: 16px;
        }

        .hero-buttons {
            flex-direction: column;
        }

        .film-card {
            min-width: 200px;
            height: 110px;
        }

        .row-title {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 24px;
        }

        .hero-description {
            font-size: 14px;
        }

        .film-card {
            min-width: 150px;
            height: 85px;
        }

        .film-card-title {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    @if($featuredFilms->count() > 0)
        @php
            $heroFilm = $featuredFilms->first();
        @endphp
        <div class="hero">
            <div class="hero-background" style="background-image: url('{{ $heroFilm->backdrop_url ?? $heroFilm->poster_url }}');"></div>
            <div class="hero-content">
                <h1 class="hero-title">{{ $heroFilm->title }}</h1>
                <p class="hero-description">{{ Str::limit($heroFilm->description, 200) }}</p>
                <div class="hero-buttons">
                    <a href="{{ route('film.show', $heroFilm->slug) }}" class="btn btn-play">
                        <i class="fas fa-play"></i> Putar
                    </a>
                    <a href="{{ route('film.show', $heroFilm->slug) }}" class="btn btn-info">
                        <i class="fas fa-info-circle"></i> Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Trending Section -->
    @if($trendingFilms->count() > 0)
        <div class="row-section">
            <h2 class="row-title">Sedang Trending</h2>
            <div class="film-row">
                @foreach($trendingFilms as $film)
                    <a href="{{ route('film.show', $film->slug) }}" style="text-decoration: none; color: inherit;">
                        <div class="film-card" style="background-image: url('{{ $film->poster_url }}');">
                            <div class="film-card-rating">
                                <i class="fas fa-star" style="color: #ffd700;"></i>
                                {{ number_format($film->rating, 1) }}
                            </div>
                            <div class="film-card-title">{{ $film->title }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- New Releases Section -->
    @if($newReleases->count() > 0)
        <div class="row-section">
            <h2 class="row-title">Rilis Terbaru</h2>
            <div class="film-row">
                @foreach($newReleases as $film)
                    <a href="{{ route('film.show', $film->slug) }}" style="text-decoration: none; color: inherit;">
                        <div class="film-card" style="background-image: url('{{ $film->poster_url }}');">
                            <div class="film-card-rating">
                                <i class="fas fa-star" style="color: #ffd700;"></i>
                                {{ number_format($film->rating, 1) }}
                            </div>
                            <div class="film-card-title">{{ $film->title }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Category Sections -->
    @foreach($categoryFilms as $categoryName => $films)
        @if($films->count() > 0)
            <div class="row-section">
                <h2 class="row-title">{{ $categoryName }}</h2>
                <div class="film-row">
                    @foreach($films as $film)
                        <a href="{{ route('film.show', $film->slug) }}" style="text-decoration: none; color: inherit;">
                            <div class="film-card" style="background-image: url('{{ $film->poster_url }}');">
                                <div class="film-card-rating">
                                    <i class="fas fa-star" style="color: #ffd700;"></i>
                                    {{ number_format($film->rating, 1) }}
                                </div>
                                <div class="film-card-title">{{ $film->title }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <!-- Empty State -->
    @if($featuredFilms->count() == 0 && $trendingFilms->count() == 0 && $newReleases->count() == 0)
        <div class="empty-state">
            <i class="fas fa-film"></i>
            <h2>Belum Ada Film</h2>
            <p>Film akan segera ditambahkan. Nantikan update terbaru!</p>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Scroll film rows with mouse wheel
    document.querySelectorAll('.film-row').forEach(row => {
        row.addEventListener('wheel', (e) => {
            e.preventDefault();
            row.scrollLeft += e.deltaY;
        });
    });
</script>
@endpush
