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
    .badge-success { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-warning { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-danger { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
</style>

<div class="admin-card">
    <div class="admin-card-header">🛒 All Orders</div>
    <div class="admin-card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Pharmacy</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td><span style="font-family:monospace;color:#2980b9;font-weight:600;">#PL-{{ sprintf('%04d', $o->id) }}</span></td>
                    <td><strong>{{ $o->customer->name }}</strong></td>
                    <td>{{ $o->pharmacy->name }}</td>
                    <td>LKR {{ number_format($o->total_amount, 2) }}</td>
                    <td>
                        @if($o->status == 'delivered')
                            <span class="badge-success">Delivered</span>
                        @elseif($o->status == 'cancelled')
                            <span class="badge-danger">Cancelled</span>
                        @else
                            <span class="badge-warning">{{ ucfirst($o->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection