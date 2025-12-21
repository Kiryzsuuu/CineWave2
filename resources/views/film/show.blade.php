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

        <div class="comments-section">
            <h2>Ratings & Comments</h2>

            <div class="ratings-summary">
                <div class="ratings-average">
                    <span class="avg-number">{{ $userRatingAvg ? number_format((float) $userRatingAvg, 1) : '0.0' }}</span>
                    <span class="avg-stars">/ 5</span>
                </div>
                <div class="ratings-count">
                    {{ number_format((int) $userRatingCount) }} rating{{ ((int) $userRatingCount) === 1 ? '' : 's' }}
                </div>
            </div>

            @if(session('success'))
                <div class="comment-flash success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="comment-flash error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @auth
                <div class="comment-form">
                    <h3>{{ $myComment ? 'Update Your Rating & Comment' : 'Leave a Rating & Comment' }}</h3>
                    <form action="{{ route('film.comments.store', $film->slug) }}" method="POST">
                        @csrf

                        <label for="rating">Rating</label>
                        <select name="rating" id="rating" required>
                            @php($selectedRating = old('rating', $myComment?->rating))
                            <option value="">Select</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ (string)$selectedRating === (string)$i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <label for="body">Comment</label>
                        <textarea name="body" id="body" rows="4" required placeholder="Write your comment...">{{ old('body', $myComment?->body) }}</textarea>

                        <button type="submit" class="btn-comment">{{ $myComment ? 'Update' : 'Post' }}</button>
                    </form>
                </div>
            @else
                <div class="comment-guest">
                    <a href="{{ route('login') }}" class="btn-comment">Login to rate & comment</a>
                </div>
            @endauth

            <div class="comment-list">
                @if(($topComments ?? collect())->count() === 0)
                    <div class="comment-empty">No comments yet. Be the first!</div>
                @else
                    @foreach($topComments as $comment)
                        <div class="comment-item" id="comment-{{ (string) $comment->id }}">
                            <div class="comment-header">
                                <div class="comment-author">
                                    <strong>{{ $comment->user_name ?? 'User' }}</strong>
                                    @if(auth()->check() && (string) $comment->user_id === (string) auth()->id())
                                        <span class="comment-badge">You</span>
                                    @endif
                                </div>
                                <div class="comment-meta">
                                    @if(!is_null($comment->rating))
                                        <span class="comment-rating"><i class="fas fa-star"></i> {{ (int) $comment->rating }}/5</span>
                                    @endif
                                    <span class="comment-date">{{ optional($comment->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="comment-body">{!! nl2br(e($comment->body)) !!}</div>

                            @auth
                                <div class="reply-form">
                                    <form action="{{ route('comments.reply', (string) $comment->id) }}" method="POST">
                                        @csrf
                                        <textarea name="body" rows="2" required placeholder="Write a reply..."></textarea>
                                        <button type="submit" class="btn-reply">Reply</button>
                                    </form>
                                </div>
                            @endauth

                            @php($replies = ($repliesByParent[(string) $comment->id] ?? collect()))
                            @if($replies->count() > 0)
                                <div class="reply-list">
                                    @foreach($replies as $reply)
                                        <div class="reply-item">
                                            <div class="reply-header">
                                                <strong>{{ $reply->user_name ?? 'User' }}</strong>
                                                <span class="reply-date">{{ optional($reply->created_at)->diffForHumans() }}</span>
                                            </div>
                                            <div class="reply-body">{!! nl2br(e($reply->body)) !!}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
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

.comments-section {
    margin-top: 60px;
    background: rgba(20, 20, 20, 0.6);
    border-radius: 8px;
    padding: 20px;
}

.comments-section h2 {
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.ratings-summary {
    display: flex;
    gap: 20px;
    align-items: baseline;
    margin-bottom: 20px;
    color: #999;
}

.ratings-average {
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.avg-number {
    font-size: 2rem;
    font-weight: bold;
    color: #ffd700;
}

.avg-stars {
    color: #999;
}

.comment-flash {
    margin: 10px 0 20px;
    padding: 12px 14px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.08);
    color: #e5e5e5;
}

.comment-flash.success {
    background: rgba(40, 167, 69, 0.2);
}

.comment-flash.error {
    background: rgba(229, 9, 20, 0.15);
}

.comment-flash ul {
    margin: 0;
    padding-left: 18px;
}

.comment-form h3 {
    margin: 0 0 10px;
    font-size: 1.2rem;
}

.comment-form form {
    display: grid;
    gap: 10px;
}

.comment-form label {
    color: #999;
    font-size: 0.95rem;
}

.comment-form select,
.comment-form textarea,
.reply-form textarea {
    width: 100%;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(0, 0, 0, 0.25);
    color: #fff;
    padding: 10px;
}

.btn-comment,
.btn-reply {
    display: inline-block;
    padding: 12px 18px;
    background: #e50914;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
    width: fit-content;
}

.btn-comment:hover,
.btn-reply:hover {
    background: #f40612;
}

.comment-guest {
    margin-bottom: 20px;
}

.comment-list {
    margin-top: 25px;
    display: grid;
    gap: 15px;
}

.comment-item {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    padding: 15px;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 10px;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 8px;
}

.comment-badge {
    background: rgba(40, 167, 69, 0.25);
    color: #fff;
    font-size: 0.8rem;
    padding: 2px 8px;
    border-radius: 999px;
}

.comment-meta {
    display: flex;
    gap: 12px;
    color: #999;
    font-size: 0.9rem;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.comment-rating {
    color: #ffd700;
    font-weight: bold;
}

.comment-body {
    color: #e5e5e5;
    line-height: 1.5;
}

.reply-form {
    margin-top: 12px;
}

.reply-form form {
    display: grid;
    gap: 10px;
}

.reply-list {
    margin-top: 12px;
    padding-left: 14px;
    border-left: 2px solid rgba(255, 255, 255, 0.12);
    display: grid;
    gap: 10px;
}

.reply-item {
    background: rgba(0, 0, 0, 0.18);
    border-radius: 8px;
    padding: 10px 12px;
}

.reply-header {
    display: flex;
    gap: 10px;
    align-items: center;
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 6px;
}

.reply-header strong {
    color: #fff;
}

.reply-body {
    color: #e5e5e5;
    line-height: 1.5;
}

.comment-empty {
    color: #999;
    padding: 10px 0;
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

    .comment-header {
        flex-direction: column;
        align-items: flex-start;
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
