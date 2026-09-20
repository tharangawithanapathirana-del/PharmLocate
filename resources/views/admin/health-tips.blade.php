@extends('layouts.admin')

@section('content')
<style>
    .admin-card { background: white; border-radius: 16px; border: 1px solid #e8f0fe; box-shadow: 0 2px 12px rgba(0,0,0,0.04); overflow: hidden; }
    .admin-card-header { background: #f8fafd; padding: 14px 20px; border-bottom: 1px solid #e8f0fe; font-weight: 700; font-size: 14px; color: #183153; }
    .admin-card-body { padding: 20px; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .admin-table th { text-align: left; padding: 12px 16px; border-bottom: 2px solid #f0f4f8; color: #888; font-weight: 600; }
    .admin-table td { padding: 12px 16px; border-bottom: 1px solid #f0f4f8; color: #333; }
    .admin-table tr:last-child td { border-bottom: none; }
    .btn-delete { background: #fcebeb; color: #791f1f; border: none; border-radius: 6px; padding: 4px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
    .btn-delete:hover { background: #c0392b; color: white; }
</style>

<div class="admin-card">
    <div class="admin-card-header">💡 All Health Tips</div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tips as $t)
                <tr>
                    <td><strong>{{ $t->title }}</strong></td>
                    <td>{{ $t->user->name }}</td>
                    <td>
                        <form action="{{ route('admin.health-tips.delete', $t->id) }}" method="POST" onsubmit="return confirm('Delete this tip?')">
                            @csrf @method('DELETE')
                            <button class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $tips->links() }}
        </div>
    </div>
</div>
@endsection