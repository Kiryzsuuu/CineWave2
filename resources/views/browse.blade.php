@extends('layouts.app')

@section('title', 'Browse Films - CineWave')

@section('content')
<div class="browse-page">
    <!-- Header -->
    <div class="browse-header">
        <h1>Browse Films</h1>
        <p>Discover your next favorite movie</p>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('browse') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Search films..." value="{{ request('search') }}" class="search-input">
            </div>
            
            <div class="filter-group">
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <!-- Films Grid -->
    <div class="films-grid">
        @forelse($films as $film)
            <div class="film-card">
                <a href="{{ route('film.show', $film->slug) }}" class="film-link">
                    <div class="film-poster">
                        <img src="{{ $film->poster_url }}" alt="{{ $film->title }}">
                        <div class="film-overlay">
                            <div class="film-rating">
                                <i class="fas fa-star"></i> {{ $film->rating }}
                            </div>
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>
                    <div class="film-info">
                        <h3>{{ $film->title }}</h3>
                        <div class="film-meta">
                            <span>{{ $film->year }}</span>
                            <span>{{ $film->duration }} min</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="no-films">
                <i class="fas fa-film"></i>
                <p>No films found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($films->hasPages())
        <div class="pagination">
            {{ $films->links() }}
        </div>
    @endif
</div>

<style>
.browse-page {
    padding: 80px 4% 40px;
    min-height: 100vh;
}

.browse-header {
    text-align: center;
    margin-bottom: 40px;
}

.browse-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: #fff;
}

.browse-header p {
    color: #999;
    font-size: 1.1rem;
}

.filters-section {
    background: rgba(20, 20, 20, 0.8);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 40px;
}

.filters-form {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-input,
.filter-select {
    width: 100%;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    color: #fff;
    font-size: 1rem;
}

.search-input::placeholder {
    color: #999;
}

.filter-select option {
    background: #141414;
    color: #fff;
}

.btn-filter {
    padding: 12px 30px;
    background: #e50914;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.btn-filter:hover {
    background: #f40612;
}

.films-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
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

.film-link {
    text-decoration: none;
    color: inherit;
}

.film-poster {
    position: relative;
    padding-top: 150%;
    overflow: hidden;
}

.film-poster img {
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

.film-rating i {
    color: #ffd700;
}

.play-button {
    font-size: 3rem;
    color: #fff;
}

.film-info {
    padding: 15px;
}

.film-info h3 {
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #fff;
}

.film-meta {
    display: flex;
    gap: 10px;
    font-size: 0.9rem;
    color: #999;
}

.no-films {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.no-films i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
}

@media (max-width: 768px) {
    .films-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .filters-form {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
}
</style>
@endsection
