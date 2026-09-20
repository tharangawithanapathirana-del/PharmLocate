@extends('layouts.admin')

@section('content')
<style>
    /* 🟢 Admin Dashboard සඳහා අලුත් Styles */
    .admin-stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #e8f0fe;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all .2s;
    }
    .admin-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .admin-stat-num {
        font-size: 2.2rem;
        font-weight: 800;
        color: #183153;
        line-height: 1;
    }
    .admin-stat-lbl {
        font-size: 13px;
        color: #888;
        margin-top: 4px;
    }

    .admin-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8f0fe;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        overflow: hidden;
        height: 100%;
    }
    .admin-card-header {
        background: #f8fafd;
        padding: 14px 20px;
        border-bottom: 1px solid #e8f0fe;
        font-weight: 700;
        font-size: 14px;
        color: #183153;
    }
    .admin-card-body {
        padding: 16px 20px;
    }

    .pharmacy-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f4f8;
    }
    .pharmacy-list-item:last-child {
        border-bottom: none;
    }

    .order-list-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f4f8;
    }
    .order-list-item:last-child {
        border-bottom: none;
    }

    /* Button Styles */
    .btn-approve {
        background: #eaf3de;
        color: #27500a;
        border: none;
        border-radius: 8px;
        padding: 5px 16px;
        font-weight: 600;
        font-size: 12px;
        transition: all .2s;
    }
    .btn-approve:hover {
        background: #3b6d11;
        color: white;
    }
    .btn-reject {
        background: #fcebeb;
        color: #791f1f;
        border: none;
        border-radius: 8px;
        padding: 5px 16px;
        font-weight: 600;
        font-size: 12px;
        transition: all .2s;
    }
    .btn-reject:hover {
        background: #c0392b;
        color: white;
    }
</style>

<div class="row g-4 mb-4">
    {{-- Stats --}}
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-num">{{ $totalUsers }}</div>
            <div class="admin-stat-lbl">Total Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-num">{{ $totalOrders }}</div>
            <div class="admin-stat-lbl">Total Orders</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card">
            <div class="admin-stat-num">{{ $totalMedicines }}</div>
            <div class="admin-stat-lbl">Total Medicines</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Pending Pharmacies --}}
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">⏳ Pending Pharmacies</div>
            <div class="admin-card-body">
                @forelse($pendingPharmacies as $p)
                <div class="pharmacy-list-item">
                    <div>
                        <div style="font-weight:600; font-size:14px; color:#183153;">{{ $p->name }}</div>
                        <div style="font-size:12px; color:#888;">{{ $p->user->email }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.pharmacy.approve', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-approve">Approve</button>
                        </form>
                        <form action="{{ route('admin.pharmacy.reject', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:13px;">No pending pharmacies.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">🛒 Recent Orders</div>
            <div class="admin-card-body">
                @forelse($recentOrders as $o)
                <div class="order-list-item">
                    <div style="font-weight:600; font-size:14px; color:#183153;">
                        {{ $o->customer->name }} <span style="font-weight:400; color:#888;">—</span> {{ $o->pharmacy->name }}
                    </div>
                    <div style="font-size:13px; color:#666;">
                        LKR {{ number_format($o->total_amount, 2) }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:13px;">No recent orders.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection