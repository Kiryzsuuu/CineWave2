@extends('layouts.app')

@section('title', $film->title . ' - CineWave')

@section('content')
<div class="film-detail-page">
    <!-- Backdrop -->
    <div class="film-backdrop">
        <img src="{{ $film->backdrop_url }}" alt="{{ $film->title }}">
        <div class="backdrop-overlay"></div>
    </div>

    <!-- Film Info -->
    <div class="film-content">
        <div class="film-main">
            <div class="film-poster">
                <img src="{{ $film->poster_url }}" alt="{{ $film->title }}">
            </div>

            <div class="film-details">
                <h1>{{ $film->title }}</h1>
                
                <div class="film-meta">
                    <span class="rating">
                        <i class="fas fa-star"></i> {{ $film->rating }}
                    </span>
                    <span>{{ $film->year }}</span>
                    <span>{{ $film->duration }} min</span>
                    <span>{{ $film->language }}</span>
                </div>

                <div class="film-genres">
                    @foreach($film->genre as $genre)
                        <span class="genre-tag">{{ $genre }}</span>
                    @endforeach
                </div>

                <p class="film-description">{{ $film->description }}</p>

                <div class="film-credits">
                    <div class="credit-item">
                        <strong>Director:</strong> {{ $film->director }}
                    </div>
                    <div class="credit-item">
                        <strong>Cast:</strong> {{ implode(', ', $film->cast) }}
                    </div>
                    <div class="credit-item">
                        <strong>Country:</strong> {{ $film->country }}
                    </div>
                </div>

                <div class="film-actions">
                    <a href="{{ route('film.watch', $film->slug) }}" class="btn-watch">
                        <i class="fas fa-play"></i> Watch Now
                    </a>
                    
                    @if($film->trailer_url)
                        <a href="{{ $film->trailer_url }}" target="_blank" class="btn-trailer">
                            <i class="fas fa-video"></i> Watch Trailer
                        </a>
                    @endif

                    @auth
                        <form id="watchlistForm" action="{{ route('watchlist.toggle', $film->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-watchlist" id="watchlistBtn">
                                <i class="fas fa-heart"></i> <span id="watchlistText">Add to Watchlist</span>
                            </button>
                        </form>
                    @endauth
                </div>

                <div class="film-stats">
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span>{{ number_format($film->views_count) }} views</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-heart"></i>
                        <span>{{ number_format($film->likes_count) }} likes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Films -->
        @if($similarFilms->count() > 0)
            <div class="similar-films">
                <h2>Similar Films</h2>
                <div class="films-grid">
                    @foreach($similarFilms as $similar)
                        <div class="film-card">
                            <a href="{{ route('film.show', $similar->slug) }}">
                                <div class="film-poster-small">
                                    <img src="{{ $similar->poster_url }}" alt="{{ $similar->title }}">
                                    <div class="film-overlay">
                                        <div class="film-rating">
                                            <i class="fas fa-star"></i> {{ $similar->rating }}
                                        </div>
                                        <div class="play-button">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="film-info">
                                    <h3>{{ $similar->title }}</h3>
                                    <div class="film-meta-small">
                                        <span>{{ $similar->year }}</span>
                                        <span>{{ $similar->duration }} min</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.film-detail-page {
    position: relative;
    min-height: 100vh;
    color: #fff;
}

.film-backdrop {
    position: relative;
    width: 100%;
    height: 70vh;
    overflow: hidden;
}

.film-backdrop img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.backdrop-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, #141414 0%, transparent 100%);
}

.film-content {
    position: relative;
    margin-top: -200px;
    padding: 0 4% 40px;
}

.film-main {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 40px;
    margin-bottom: 60px;
}

.film-poster img {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.film-details h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.film-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 1.1rem;
    color: #999;
}

.rating {
    color: #ffd700;
    font-weight: bold;
}

.film-genres {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(255, 255, 255, 0.1);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.film-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 25px;
    color: #e5e5e5;
}

.film-credits {
    margin-bottom: 30px;
}

.credit-item {
    margin-bottom: 10px;
    font-size: 1rem;
    color: #999;
}

.credit-item strong {
    color: #fff;
    margin-right: 8px;
}

.film-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.btn-watch {
    padding: 15px 40px;
    background: #e50914;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 1.1rem;
    font-weight: bold;
    transition: background 0.3s;
    border: none;
    cursor: pointer;
}

.btn-watch:hover {
    background: #f40612;
}

.btn-trailer,
.btn-watchlist {
    padding: 15px 30px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 1rem;
    transition: background 0.3s;
    border: none;
    cursor: pointer;
}

.btn-trailer:hover,
.btn-watchlist:hover {
    background: rgba(255, 255, 255, 0.3);
}

.film-stats {
    display: flex;
    gap: 30px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #999;
}

.stat-item i {
    font-size: 1.2rem;
}

.similar-films {
    margin-top: 60px;
}

.similar-films h2 {
    font-size: 1.8rem;
    margin-bottom: 25px;
}

.films-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
}

.film-card {
    background: rgba(20, 20, 20, 0.8);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.film-card:hover {
    transform: scale(1.05);
}

.film-card a {
    text-decoration: none;
    color: inherit;
}

.film-poster-small {
    position: relative;
    padding-top: 150%;
    overflow: hidden;
}

.film-poster-small img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.film-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.film-card:hover .film-overlay {
    opacity: 1;
}

.film-rating {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.play-button {
    font-size: 2.5rem;
    color: #fff;
}

.film-info {
    padding: 15px;
}

.film-info h3 {
    font-size: 1rem;
    margin-bottom: 8px;
    color: #fff;
}

.film-meta-small {
    display: flex;
    gap: 10px;
    font-size: 0.85rem;
    color: #999;
}

@media (max-width: 768px) {
    .film-main {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .film-poster {
        max-width: 300px;
        margin: 0 auto;
    }
    
    .film-details h1 {
        font-size: 1.8rem;
    }
    
    .films-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}
</style>

@auth
<script>
// Check if film is in watchlist on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("watchlist.check", $film->id) }}')
        .then(response => response.json())
        .then(data => {
            const btn = document.getElementById('watchlistBtn');
            const text = document.getElementById('watchlistText');
            const icon = btn.querySelector('i');
            
            if (data.in_watchlist) {
                icon.className = 'fas fa-check';
                text.textContent = 'In Watchlist';
                btn.style.background = 'rgba(40, 167, 69, 0.3)';
            }
        });
    
    // Handle form submission
    document.getElementById('watchlistForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('watchlistBtn');
        const text = document.getElementById('watchlistText');
        const icon = btn.querySelector('i');
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.action === 'added') {
                    icon.className = 'fas fa-check';
                    text.textContent = 'In Watchlist';
                    btn.style.background = 'rgba(40, 167, 69, 0.3)';
                } else {
                    icon.className = 'fas fa-heart';
                    text.textContent = 'Add to Watchlist';
                    btn.style.background = 'rgba(255, 255, 255, 0.2)';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endauth
@endsection
