<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* 🟢 සම්පූර්ණ Layout Styles (Sidebar + Main) */
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
        .content { padding: 28px; }

        /* 🟢 Stat Cards සහ Table Styles */
        .stat-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .stat-lbl { font-size: 13px; color: #888; margin-top: 3px; }

        .section-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .orders-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .orders-table th { background: #f4f7fb; color: #555; font-weight: 600; padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; }
        .orders-table th:first-child { border-radius: 10px 0 0 10px; }
        .orders-table th:last-child { border-radius: 0 10px 10px 0; }
        .orders-table td { padding: 12px 14px; border-bottom: 1px solid #f0f4f8; color: #333; vertical-align: middle; }
        .orders-table tr:last-child td { border-bottom: none; }

        .badge-pending { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-processing { background: #e6f1fb; color: #0c447c; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-cancelled { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-rx { background: #eeedfe; color: #3c3489; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        .btn-verify { background: #eaf3de; color: #27500a; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-verify:hover { background: #3b6d11; color: white; }
        .btn-reject { background: #fcebeb; color: #791f1f; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-reject:hover { background: #a32d2d; color: white; }
        .btn-view-rx { background: #e6f1fb; color: #0c447c; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all .2s; }
        .btn-view-rx:hover { background: #2980b9; color: white; }

        .filter-bar { background: white; border-radius: 12px; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
    </style>
</head>
<body>

<!-- 🟢 Sidebar එක ඇතුලත් කිරීම (මේක දැනටමත් ඔබේ ෆයිල් එකේ තියෙනවා නම් හරි) -->
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Prescriptions</div>
            <div class="topbar-sub">Manage all prescription uploads from customers</div>
        </div>
    </div>

    <div class="content">
        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#e6f1fb">📋</div>
                    <div><div class="stat-num" style="color:#1a5276">{{ $totalPrescriptions }}</div><div class="stat-lbl">Total</div></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#faeeda">⏳</div>
                    <div><div class="stat-num" style="color:#854f0b">{{ $pendingPrescriptions }}</div><div class="stat-lbl">Pending</div></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eaf3de">✅</div>
                    <div><div class="stat-num" style="color:#27500a">{{ $verifiedPrescriptions }}</div><div class="stat-lbl">Verified / Processed</div></div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('pharmacy.prescriptions') }}" class="filter-bar d-flex gap-3 align-items-center flex-wrap">
            <select name="status" class="form-select" style="width:200px">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" style="background:#1a5276;color:white;border:none;padding:8px 20px;border-radius:10px;font-weight:600;">Filter</button>
        </form>

        <!-- Prescriptions Table -->
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">All prescriptions</h6>
                <small class="text-muted">Showing {{ $prescriptions->total() }} prescriptions</small>
            </div>
            <div style="overflow-x:auto">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Medicine</th>
                            <th>Uploaded At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span style="font-family:monospace;color:#2980b9;font-weight:600">#PL-{{ sprintf('%04d', $order->id) }}</span></td>
                            <td>
                                <div style="font-weight:600">{{ $order->customer->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $order->customer->phone ?? '-' }}</small>
                            </td>
                            <td>{{ $order->items->first()->medicine->name ?? 'N/A' }} ×{{ $order->items->first()->quantity ?? 0 }}</td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td>
                                @php
                                    $statusColor = $order->status == 'pending' ? 'badge-pending' : ($order->status == 'cancelled' ? 'badge-cancelled' : 'badge-processing');
                                @endphp
                                <span class="{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    {{-- View Rx Button --}}
                                    @if($order->prescription_image)
                                        <a href="{{ asset('storage/' . $order->prescription_image) }}" target="_blank" class="btn-view-rx">
                                            📄 View Rx
                                        </a>
                                    @endif

                                    {{-- Verify / Reject Buttons --}}
                                    @if($order->status == 'pending')
                                        <button class="btn-verify" onclick="verifyRx({{ $order->id }})">✅ Verify</button>
                                        <button class="btn-reject" onclick="rejectRx({{ $order->id }})">❌ Reject</button>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No prescriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Verify සහ Reject සඳහා AJAX කේත
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