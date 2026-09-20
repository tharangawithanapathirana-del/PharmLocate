<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* ඔබේ කලින් තිබුණු CSS ඔක්කොම එලෙසම */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; display: flex; }
        .sidebar { width: 240px; min-height: 100vh; background: linear-gradient(180deg, #0f1e32 0%, #1a5276 100%); position: fixed; top: 0; left: 0; display: flex; flex-direction: column; padding: 24px 0; z-index: 100; }
        .sidebar-brand { padding: 0 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 12px; }
        .sidebar-brand .icon { font-size: 36px; margin-bottom: 4px; }
        .sidebar-brand .name { font-size: 18px; font-weight: 700; color: white; }
        .sidebar-brand .sub { font-size: 12px; color: rgba(255,255,255,0.5); }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: rgba(255,255,255,0.6); font-size: 14px; cursor: pointer; transition: all .2s; border-left: 3px solid transparent; text-decoration: none; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active { background: rgba(255,255,255,0.12); color: white; border-left-color: #5dade2; font-weight: 600; }
        .nav-icon { font-size: 18px; width: 24px; text-align: center; }
        .nav-badge { background: #e67e22; color: white; font-size: 11px; padding: 2px 8px; border-radius: 20px; margin-left: auto; font-weight: 700; }
        .sidebar-footer { margin-top: auto; padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .main { margin-left: 240px; flex: 1; min-height: 100vh; }
        .topbar { background: white; padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 50; }
        .topbar-title { font-size: 18px; font-weight: 700; color: #1a5276; }
        .topbar-sub { font-size: 13px; color: #888; }
        .online-dot { width: 8px; height: 8px; background: #27ae60; border-radius: 50%; display: inline-block; margin-right: 6px; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .content { padding: 28px; }
        .stat-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: none; display: flex; align-items: center; gap: 16px; transition: all .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .stat-lbl { font-size: 13px; color: #888; margin-top: 3px; }
        .section-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .section-title { font-size: 15px; font-weight: 700; color: #1a3a4f; margin-bottom: 16px; }
        .orders-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .orders-table th { background: #f4f7fb; color: #555; font-weight: 600; padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .orders-table th:first-child { border-radius: 10px 0 0 10px; }
        .orders-table th:last-child { border-radius: 0 10px 10px 0; }
        .orders-table td { padding: 12px 14px; border-bottom: 1px solid #f0f4f8; color: #333; vertical-align: middle; }
        .orders-table tr:last-child td { border-bottom: none; }
        .orders-table tr:hover td { background: #fafcff; }
        .badge-pending { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-processing { background: #e6f1fb; color: #0c447c; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-delivered { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-cancelled { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-low { background: #fcebeb; color: #791f1f; font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
        .status-select { border: 1.5px solid #e0e8f0; border-radius: 8px; padding: 5px 10px; font-size: 12px; color: #1a5276; background: white; cursor: pointer; }
        .status-select:focus { outline: none; border-color: #2980b9; }
        .btn-verify { background: #eaf3de; color: #27500a; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-verify:hover { background: #3b6d11; color: white; }
        .btn-reject { background: #fcebeb; color: #791f1f; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-reject:hover { background: #a32d2d; color: white; }
        .stock-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f4f8; }
        .stock-item:last-child { border-bottom: none; }
        .rx-item { padding: 12px 0; border-bottom: 1px solid #f0f4f8; }
        .rx-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

@include('layouts.sidebar')

<!-- Main content -->
<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Dashboard</div>
            <div class="topbar-sub">Welcome back, <strong>{{ auth()->user()->name }}</strong></div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:13px;color:#27ae60"><span class="online-dot"></span>Online</span>
            <div style="width:36px;height:36px;background:#e6f1fb;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px">👤</div>
        </div>
    </div>

    <div class="content">
        <!-- Stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#e6f1fb">🛒</div>
                    <div>
                        <div class="stat-num" style="color:#1a5276">{{ $totalOrders }}</div>
                        <div class="stat-lbl">Total orders</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#faeeda">⏳</div>
                    <div>
                        <div class="stat-num" style="color:#854f0b">{{ $pendingOrders }}</div>
                        <div class="stat-lbl">Pending orders</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eaf3de">💊</div>
                    <div>
                        <div class="stat-num" style="color:#27500a">{{ $totalMedicines }}</div>
                        <div class="stat-lbl">Stock items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fcebeb">⚠️</div>
                    <div>
                        <div class="stat-num" style="color:#a32d2d">{{ $lowStockItems->count() }}</div>
                        <div class="stat-lbl">Low stock alerts</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent orders -->
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">Recent orders</div>
                        <a href="/pharmacy-orders" style="font-size:13px;color:#2980b9;font-weight:600;text-decoration:none">View all →</a>
                    </div>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Medicine</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><span style="font-family:monospace;color:#2980b9;font-weight:600">#PL-{{ sprintf('%04d', $order->id) }}</span></td>
                                <td>
                                    <div style="font-weight:600">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $order->customer->phone ?? '-' }}</small>
                                </td>
                                <td>{{ $order->items->first()->medicine->name ?? 'N/A' }} ×{{ $order->items->first()->quantity ?? 0 }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-pending';
                                        if($order->status == 'processing') $badgeClass = 'badge-processing';
                                        if($order->status == 'delivered') $badgeClass = 'badge-delivered';
                                        if($order->status == 'cancelled') $badgeClass = 'badge-cancelled';
                                    @endphp
                                    <span class="{{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    {{-- 🟢 මෙතන Verify/Reject ඉවත් කරලා, Status Dropdown එක විතරක් තැබුවා --}}
                                    @if(in_array($order->status, ['pending', 'confirmed', 'processing', 'dispatched']))
                                        <select class="status-select" onchange="updateStatus({{ $order->id }}, this.value)">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    @elseif($order->status == 'delivered' || $order->status == 'cancelled')
                                        <a href="#" style="font-size:12px;color:#2980b9;font-weight:600">View</a>
                                    @else
                                        <span class="text-muted" style="font-size:12px">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No recent orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-lg-4">
                <!-- Low stock -->
                <div class="section-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">⚠️ Low stock alerts</h6>
                        <a href="/stock-management" style="font-size:13px;color:#2980b9;font-weight:600;text-decoration:none">View all →</a>
                    </div>
                    @forelse($lowStockItems as $medicine)
                    <div class="stock-item">
                        <div>
                            <div style="font-size:13px;font-weight:600">{{ $medicine->name }}</div>
                            <small class="text-muted">{{ $medicine->quantity }} tablets remaining</small>
                        </div>
                        @if($medicine->quantity <= 5)
                            <span class="badge-low" style="background:#fcebeb;color:#791f1f;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600">Critical</span>
                        @else
                            <span class="badge-low">Low</span>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted" style="font-size:13px">
                        ✅ All medicines are sufficiently stocked
                    </div>
                    @endforelse
                    @if($lowStockItems->count() > 0)
                    <a href="/stock-management" style="display:block;text-align:center;margin-top:14px;font-size:13px;color:#2980b9;font-weight:600;text-decoration:none;padding:8px;background:#e6f1fb;border-radius:8px">
                        Manage stock →
                    </a>
                    @endif
                </div>

                <!-- Pending prescriptions -->
                <div class="section-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title mb-0">📋 Pending prescriptions</div>
                        {{-- 🟢 View All Button එක එකතු කරලා --}}
                        <a href="/pharmacy-orders" style="font-size:13px;color:#2980b9;font-weight:600;text-decoration:none">View all →</a>
                    </div>
                    @forelse($pendingPrescriptions as $order)
                    <div class="rx-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#2980b9">#PL-{{ sprintf('%04d', $order->id) }}</div>
                                <div style="font-size:12px;color:#555">{{ $order->customer->name ?? 'N/A' }} — {{ $order->items->first()->medicine->name ?? 'N/A' }}</div>
                            </div>
                            <span style="font-size:11px;background:#faeeda;color:#633806;padding:2px 8px;border-radius:20px">Pending</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            {{-- 🟢 Prescription එක බලාගන්න View Rx බොත්තම --}}
                            @if($order->prescription_image)
                                <a href="{{ asset('storage/' . $order->prescription_image) }}" target="_blank" 
                                   style="background:#e6f1fb;color:#0c447c;border:none;border-radius:8px;padding:4px 12px;font-size:12px;font-weight:600;text-decoration:none;">
                                    📄 View Rx
                                </a>
                            @endif
                            <button class="btn-verify" onclick="verifyRx({{ $order->id }})">✅ Verify</button>
                            <button class="btn-reject" onclick="rejectRx({{ $order->id }})">❌ Reject</button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted" style="font-size:13px">No pending prescriptions.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🟢 JavaScript: Button Actions සඳහා --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Order Status Update කිරීමට (AJAX)
    function updateStatus(orderId, status) {
        if(!confirm('Are you sure you want to update status to "' + status + '"?')) return;

        fetch('/order/' + orderId + '/status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error: Could not update status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        });
    }

    // Prescription Verify කිරීමට (AJAX)
    function verifyRx(orderId) {
        if(!confirm('Verify this prescription?')) return;
        fetch('/order/' + orderId + '/verify-prescription', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error: Could not verify.');
            }
        });
    }

    // Prescription Reject කිරීමට (AJAX)
    function rejectRx(orderId) {
        if(!confirm('Reject this prescription?')) return;
        fetch('/order/' + orderId + '/reject-prescription', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error: Could not reject.');
            }
        });
    }
</script>
</body>
</html>