@extends('layouts.app')

@section('title', 'Manage Films - Admin')

@push('styles')
<style>
    .admin-container {
        padding: 40px 4%;
        min-height: calc(100vh - 140px);
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-header h1 {
        font-size: 32px;
        font-weight: 700;
    }

    .table-container {
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    th {
        background: rgba(0,0,0,0.3);
        font-weight: 600;
        font-size: 14px;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 12px;
        margin-right: 5px;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }

    .film-thumb {
        width: 60px;
        height: 90px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Manage Films</h1>
        <a href="{{ route('admin.films.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Film
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($films as $film)
                    <tr>
                        <td>
                            <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="film-thumb">
                        </td>
                        <td>
                            <strong>{{ $film->title }}</strong><br>
                            <small style="color: #999;">{{ Str::limit($film->description, 80) }}</small>
                        </td>
                        <td>{{ $film->year }}</td>
                        <td>
                            <i class="fas fa-star" style="color: #ffd700;"></i> {{ $film->rating }}
                        </td>
                        <td>{{ $film->duration }} min</td>
                        <td>
                            @if($film->is_featured)
                                <span class="badge badge-success">Featured</span>
                            @endif
                            @if($film->is_trending)
                                <span class="badge" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">Trending</span>
                            @endif
                            @if($film->is_new_release)
                                <span class="badge" style="background: rgba(0, 123, 255, 0.2); color: #007bff;">New</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-secondary btn-small">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this film?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small" style="background: #dc3545;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-film" style="font-size: 48px; opacity: 0.3;"></i><br><br>
                            No films found. <a href="{{ route('admin.films.create') }}" style="color: var(--netflix-red);">Add your first film</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($films->hasPages())
        <div style="margin-top: 30px;">
            {{ $films->links() }}
        </div>
    @endif
</div>
@endsection
