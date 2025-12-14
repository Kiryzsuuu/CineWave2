@extends('layouts.app')

@section('title', 'Admin Dashboard - CineWave')

@push('styles')
<style>
    .admin-container {
        padding: 40px 4%;
        min-height: calc(100vh - 140px);
    }

    .admin-header {
        margin-bottom: 40px;
    }

    .admin-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(255,255,255,0.05);
        padding: 24px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .stat-card-title {
        font-size: 14px;
        color: var(--netflix-gray);
        margin-bottom: 8px;
    }

    .stat-card-value {
        font-size: 36px;
        font-weight: 700;
        color: var(--netflix-red);
    }

    .admin-nav {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .admin-nav .btn {
        padding: 12px 24px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        margin: 30px 0 20px;
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
        color: var(--netflix-gray);
    }

    td {
        font-size: 14px;
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

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .badge-admin {
        background: rgba(229, 9, 20, 0.2);
        color: var(--netflix-red);
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Admin Dashboard</h1>
        <p style="color: var(--netflix-gray);">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-title">Total Users</div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Total Films</div>
            <div class="stat-card-value">{{ $stats['total_films'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Categories</div>
            <div class="stat-card-value">{{ $stats['total_categories'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Active Subscriptions</div>
            <div class="stat-card-value">{{ $stats['active_subscriptions'] }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-nav">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
            <i class="fas fa-users"></i> Manage Users
        </a>
        <a href="{{ route('admin.films.index') }}" class="btn btn-primary">
            <i class="fas fa-film"></i> Manage Films
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
            <i class="fas fa-folder"></i> Manage Categories
        </a>
    </div>

    <!-- Recent Users -->
    <h2 class="section-title">Recent Users</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_users'] as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_verified)
                                <span class="badge badge-success">Verified</span>
                            @else
                                <span class="badge badge-danger">Unverified</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge">User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--netflix-gray);">No users yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Recent Activities -->
    <h2 class="section-title">Recent Activities</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_activities'] as $activity)
                    <tr>
                        <td><span class="badge">{{ $activity->type }}</span></td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->ip_address }}</td>
                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--netflix-gray);">No activities yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
