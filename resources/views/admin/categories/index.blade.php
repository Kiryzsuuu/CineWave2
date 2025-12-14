@extends('layouts.app')

@section('title', 'Manage Categories - Admin')

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
        <h1>Manage Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><strong>#{{ $category->order }}</strong></td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-secondary btn-small">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-folder" style="font-size: 48px; opacity: 0.3;"></i><br><br>
                            No categories found. <a href="{{ route('admin.categories.create') }}" style="color: var(--netflix-red);">Add your first category</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div style="margin-top: 30px;">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
