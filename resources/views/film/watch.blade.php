@extends('layouts.app')

@section('title', 'Watch ' . $film->title . ' - CineWave')

@section('content')
<div class="watch-page">
    <!-- Video Player -->
    <div class="video-container">
        <div class="video-player">
            @if($film->video_url)
                @if(str_contains($film->video_url, 'youtube.com') || str_contains($film->video_url, 'youtu.be'))
                    @php
                        $videoId = null;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?\/]+)/', $film->video_url, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe 
                            width="100%" 
                            height="100%" 
                            src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&rel=0" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <div class="video-placeholder">
                            <i class="fas fa-film"></i>
                            <p>Video player will appear here</p>
                        </div>
                    @endif
                @else
                    <video controls autoplay>
                        <source src="{{ $film->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
            @else
                <div class="video-placeholder">
                    <i class="fas fa-film"></i>
                    <p>Video not available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Film Information -->
    <div class="watch-info">
        <div class="info-header">
            <div>
                <h1>{{ $film->title }}</h1>
                <div class="film-meta">
                    <span class="rating">
                        <i class="fas fa-star"></i> {{ $film->rating }}
                    </span>
                    <span>{{ $film->year }}</span>
                    <span>{{ $film->duration }} min</span>
                </div>
            </div>
            <a href="{{ route('film.show', $film->slug) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
        </div>

        <div class="film-description">
            <h3>About this film</h3>
            <p>{{ $film->description }}</p>
        </div>

        <div class="film-details-grid">
            <div class="detail-item">
                <strong>Director</strong>
                <span>{{ $film->director }}</span>
            </div>
            <div class="detail-item">
                <strong>Cast</strong>
                <span>{{ implode(', ', $film->cast) }}</span>
            </div>
            <div class="detail-item">
                <strong>Genres</strong>
                <span>{{ implode(', ', $film->genre) }}</span>
            </div>
            <div class="detail-item">
                <strong>Language</strong>
                <span>{{ $film->language }}</span>
            </div>
            <div class="detail-item">
                <strong>Country</strong>
                <span>{{ $film->country }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.watch-page {
    background: #000;
    min-height: 100vh;
    color: #fff;
}

.video-container {
    width: 100%;
    background: #000;
}

.video-player {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    background: #000;
}

.video-player iframe,
.video-player video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.video-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #141414;
    color: #999;
}

.video-placeholder i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.watch-info {
    padding: 40px 4%;
    max-width: 1200px;
    margin: 0 auto;
}

.info-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

.info-header h1 {
    font-size: 2rem;
    margin-bottom: 10px;
}

.film-meta {
    display: flex;
    gap: 15px;
    font-size: 1rem;
    color: #999;
}

.rating {
    color: #ffd700;
    font-weight: bold;
}

.btn-back {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
    white-space: nowrap;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
}

.film-description {
    margin-bottom: 30px;
}

.film-description h3 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    color: #fff;
}

.film-description p {
    font-size: 1.05rem;
    line-height: 1.6;
    color: #e5e5e5;
}

.film-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.detail-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 15px 20px;
    border-radius: 8px;
    border-left: 3px solid #e50914;
}

.detail-item strong {
    display: block;
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item span {
    color: #fff;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .info-header {
        flex-direction: column;
    }
    
    .info-header h1 {
        font-size: 1.5rem;
    }
    
    .film-details-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
