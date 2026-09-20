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
    .btn-danger { background: #fcebeb; color: #791f1f; border: none; border-radius: 6px; padding: 4px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
    .btn-danger:hover { background: #c0392b; color: white; }
</style>

<div class="admin-card">
    <div class="admin-card-header">👤 All Users</div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td><span style="background:#f0f4f8;padding:2px 10px;border-radius:12px;font-size:12px;font-weight:600;">{{ $u->role }}</span></td>
                    <td>
                        @if($u->role != 'admin')
                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger">Delete</button>
                            </form>
                        @else
                            <span style="color:#aaa;font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection