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
    .badge-active { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-pending { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-rejected { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
</style>

<div class="admin-card">
    <div class="admin-card-header">🏥 All Pharmacies</div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pharmacies as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td><strong>{{ $p->name }}</strong></td>
                    <td>{{ $p->user->email }}</td>
                    <td>{{ $p->address }}</td>
                    <td>
                        @if($p->status == 'active')
                            <span class="badge-active">Active</span>
                        @elseif($p->status == 'pending')
                            <span class="badge-pending">Pending</span>
                        @else
                            <span class="badge-rejected">Rejected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $pharmacies->links() }}
        </div>
    </div>
</div>
@endsection