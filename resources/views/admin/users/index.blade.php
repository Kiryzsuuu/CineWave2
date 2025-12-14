@extends('layouts.app')

@section('title', 'Manage Users - Admin')

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

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
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
                                <span class="badge" style="background: rgba(229, 9, 20, 0.2); color: var(--netflix-red);">Admin</span>
                            @else
                                User
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-small">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small" style="background: #dc3545;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
