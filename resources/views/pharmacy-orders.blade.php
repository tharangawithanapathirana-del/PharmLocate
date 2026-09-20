<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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

        .stat-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 16px; transition: all .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .stat-lbl { font-size: 13px; color: #888; margin-top: 3px; }

        .section-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }

        .orders-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .orders-table th { background: #f4f7fb; color: #555; font-weight: 600; padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .orders-table th:first-child { border-radius: 10px 0 0 10px; }
        .orders-table th:last-child { border-radius: 0 10px 10px 0; }
        .orders-table td { padding: 12px 14px; border-bottom: 1px solid #f0f4f8; color: #333; vertical-align: middle; }
        .orders-table tr:last-child td { border-bottom: none; }
        .orders-table tr:hover td { background: #fafcff; }

        .badge-pending { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-confirmed { background: #e6f1fb; color: #0c447c; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-processing { background: #eeedfe; color: #3c3489; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-dispatched { background: #faeeda; color: #854f0b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-delivered { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-cancelled { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-rx { background: #eeedfe; color: #3c3489; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        .status-select { border: 1.5px solid #e0e8f0; border-radius: 8px; padding: 5px 10px; font-size: 12px; color: #1a5276; background: white; cursor: pointer; }
        .status-select:focus { outline: none; border-color: #2980b9; }

        .btn-view { background: #e6f1fb; color: #0c447c; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; text-decoration: none; display: inline-block; }
        .btn-view:hover { background: #2980b9; color: white; }

        .filter-bar { background: white; border-radius: 12px; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }

        .order-detail-card { background: #f4f7fb; border-radius: 12px; padding: 16px; margin-top: 12px; }
        .rx-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #e0e8f0; }

        .modal-content { border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
        .modal-header { background: linear-gradient(135deg, #0f1e32, #1a5276); border-radius: 16px 16px 0 0; padding: 20px 24px; }
        .modal-title { color: white; font-weight: 600; }
        .modal-header .btn-close { filter: invert(1); }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f4f8; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
    </style>
</head>
<body>

@include('layouts.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Orders</div>
            <div class="topbar-sub">Manage incoming orders from customers</div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert mb-4" style="background:#eaf3de;border:none;border-radius:10px;color:#27500a;padding:12px 16px">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#e6f1fb">🛒</div>
                    <div><div class="stat-num" style="color:#1a5276">{{ $totalOrders }}</div><div class="stat-lbl">Total</div></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#faeeda">⏳</div>
                    <div><div class="stat-num" style="color:#854f0b">{{ $pending }}</div><div class="stat-lbl">Pending</div></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#e6f1fb">✅</div>
                    <div><div class="stat-num" style="color:#0c447c">{{ $confirmed }}</div><div class="stat-lbl">Confirmed</div></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eeedfe">⚙️</div>
                    <div><div class="stat-num" style="color:#3c3489">{{ $processing }}</div><div class="stat-lbl">Processing</div></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eaf3de">🚚</div>
                    <div><div class="stat-num" style="color:#27500a">{{ $dispatched }}</div><div class="stat-lbl">Dispatched</div></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eaf3de">📦</div>
                    <div><div class="stat-num" style="color:#27500a">{{ $delivered }}</div><div class="stat-lbl">Delivered</div></div>
                </div>
            </div>
        </div>

        <!-- Filter bar -->
        <form method="GET" action="{{ route('pharmacy.orders') }}" class="filter-bar d-flex gap-3 align-items-center flex-wrap">
            <div style="flex:1;min-width:200px">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search by order ID or customer name..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select" style="width:160px">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="type" class="form-select" style="width:160px">
                <option value="">All types</option>
                <option value="delivery" {{ request('type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                <option value="pickup" {{ request('type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
            </select>
            <button type="submit" style="background:#1a5276;color:white;border:none;padding:8px 20px;border-radius:10px;font-weight:600;">Filter</button>
        </form>

        <!-- Orders table -->
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">All orders</h6>
                <small class="text-muted" id="order-count">Showing {{ $orders->total() }} orders</small>
            </div>
            <div style="overflow-x:auto">
                <table class="orders-table" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Medicine</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Type</th>
                            <th>RX</th>
                            <th>Status</th>
                            <th>Update status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr data-status="{{ $order->status }}" data-type="{{ $order->delivery_type }}">
                            <td><span style="font-family:monospace;color:#2980b9;font-weight:600">#PL-{{ sprintf('%04d', $order->id) }}</span></td>
                            <td>
                                <div style="font-weight:600">{{ $order->customer->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $order->customer->phone ?? '-' }}</small>
                            </td>
                            <td>{{ $order->items->first()->medicine->name ?? 'N/A' }}</td>
                            <td>{{ $order->items->first()->quantity ?? 0 }}</td>
                            <td style="font-weight:600;color:#1a5276">LKR {{ number_format($order->total_amount, 2) }}</td>
                            <td><span style="font-size:12px">{{ $order->delivery_type === 'delivery' ? '🚚 Delivery' : '🏪 Pickup' }}</span></td>
                            <td>
                                @if($order->prescription_image)
                                    <span class="badge-rx">Rx uploaded</span>
                                @elseif($order->status == 'pending')
                                    <span style="font-size:12px;color:#888">—</span>
                                @else
                                    <span class="badge-rx">Rx verified</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'pending' => 'badge-pending',
                                        'confirmed' => 'badge-confirmed',
                                        'processing' => 'badge-processing',
                                        'dispatched' => 'badge-dispatched',
                                        'delivered' => 'badge-delivered',
                                        'cancelled' => 'badge-cancelled'
                                    ];
                                @endphp
                                <span class="{{ $statusMap[$order->status] ?? 'badge-pending' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                {{-- 🟢 මෙතන Verify/Reject බොත්තම් අයින් කරලා, Status Dropdown එක විතරක් තියෙන විදියට හැදුවා --}}
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
                                    <span class="text-muted" style="font-size:12px">Completed</span>
                                @else
                                    <span class="text-muted" style="font-size:12px">—</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-view" data-bs-toggle="modal" data-bs-target="#orderDetailModal"
                                    onclick="showOrderDetail({
                                        id: '#PL-{{ sprintf('%04d', $order->id) }}',
                                        name: '{{ $order->customer->name ?? 'N/A' }}',
                                        email: '{{ $order->customer->email ?? 'N/A' }}',
                                        phone: '{{ $order->customer->phone ?? '-' }}',
                                        medicine: '{{ $order->items->first()->medicine->name ?? 'N/A' }}',
                                        qty: '{{ $order->items->first()->quantity ?? 0 }}',
                                        total: 'LKR {{ number_format($order->total_amount, 2) }}',
                                        type: '{{ $order->delivery_type === 'delivery' ? 'Delivery' : 'Pickup' }}',
                                        address: '{{ $order->delivery_address ?? 'N/A' }}',
                                        status: '{{ ucfirst($order->status) }}',
                                        rx: '{{ $order->prescription_image ? 'Yes' : 'No' }}'
                                    })">
                                    👁️ View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>

    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📋 Order details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="section-card" style="padding:16px">
                            <h6 class="fw-bold mb-3" style="color:#1a5276">Customer information</h6>
                            <div class="detail-row"><span class="text-muted">Name</span><span class="fw-bold" id="d_name"></span></div>
                            <div class="detail-row"><span class="text-muted">Email</span><span id="d_email"></span></div>
                            <div class="detail-row"><span class="text-muted">Phone</span><span id="d_phone"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="section-card" style="padding:16px">
                            <h6 class="fw-bold mb-3" style="color:#1a5276">Order information</h6>
                            <div class="detail-row"><span class="text-muted">Order ID</span><span class="fw-bold" style="font-family:monospace;color:#2980b9" id="d_id"></span></div>
                            <div class="detail-row"><span class="text-muted">Medicine</span><span id="d_medicine"></span></div>
                            <div class="detail-row"><span class="text-muted">Quantity</span><span id="d_qty"></span></div>
                            <div class="detail-row"><span class="text-muted">Total</span><span class="fw-bold" style="color:#1a5276" id="d_total"></span></div>
                            <div class="detail-row"><span class="text-muted">Fulfillment</span><span id="d_type"></span></div>
                            <div class="detail-row"><span class="text-muted">Address</span><span id="d_address"></span></div>
                            <div class="detail-row"><span class="text-muted">Status</span><span id="d_status"></span></div>
                            <div class="detail-row"><span class="text-muted">Rx required</span><span id="d_rx"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:0.5px solid #e0e8f0;padding:16px 24px">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showOrderDetail(data) {
    document.getElementById('d_id').textContent = data.id;
    document.getElementById('d_name').textContent = data.name;
    document.getElementById('d_email').textContent = data.email;
    document.getElementById('d_phone').textContent = data.phone;
    document.getElementById('d_medicine').textContent = data.medicine;
    document.getElementById('d_qty').textContent = data.qty;
    document.getElementById('d_total').textContent = data.total;
    document.getElementById('d_type').textContent = data.type;
    document.getElementById('d_address').textContent = data.address;
    document.getElementById('d_status').textContent = data.status;
    document.getElementById('d_rx').textContent = data.rx;
}

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
</script>
</body>
</html>