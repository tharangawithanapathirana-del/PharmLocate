<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }
        .navbar { background: rgba(15,30,50,0.97); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; text-decoration: none; }
        .btn-back { background: rgba(255,255,255,0.1); color: white !important; border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 6px 16px; font-size: 13px; text-decoration: none; transition: all .2s; }
        .btn-back:hover { background: rgba(255,255,255,0.2); color: white; }
        .page-header { background: linear-gradient(135deg, #0f1e32, #1a5276); padding: 30px 0; }
        .detail-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .row-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f4f8; }
        .row-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

<nav class="navbar sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="/">💊 PharmLocate</a>
        <a href="/customer-dashboard" class="btn-back">← Back to Dashboard</a>
    </div>
</nav>

<div class="page-header text-white text-center">
    <div class="container">
        <h4 class="fw-bold mb-1">Order Details</h4>
        <p class="opacity-75 small mb-0">#PL-{{ sprintf('%04d', $order->id) }}</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="row-item"><span class="text-muted">Order ID</span><span class="fw-bold" style="color:#2980b9">#PL-{{ sprintf('%04d', $order->id) }}</span></div>
                <div class="row-item"><span class="text-muted">Medicine</span><span class="fw-bold">{{ $order->items->first()->medicine->name ?? 'N/A' }} ×{{ $order->items->first()->quantity ?? 0 }}</span></div>
                <div class="row-item"><span class="text-muted">Pharmacy</span><span class="fw-bold">{{ $order->pharmacy->name ?? 'N/A' }}</span></div>
                <div class="row-item"><span class="text-muted">Total Amount</span><span class="fw-bold" style="color:#1a5276">LKR {{ number_format($order->total_amount, 2) }}</span></div>
                <div class="row-item"><span class="text-muted">Delivery Type</span><span class="fw-bold">{{ ucfirst($order->delivery_type) }}</span></div>
                <div class="row-item"><span class="text-muted">Delivery Address</span><span class="fw-bold">{{ $order->delivery_address ?? 'N/A' }}</span></div>
                <div class="row-item"><span class="text-muted">Status</span>
                    <span class="fw-bold">
                        @if($order->status == 'pending') <span class="badge-pending">Pending</span>
                        @elseif($order->status == 'delivered') <span class="badge-delivered">Delivered</span>
                        @else <span class="badge-processing">Processing</span>
                        @endif
                    </span>
                </div>
                <div class="row-item"><span class="text-muted">Order Placed</span><span class="fw-bold">{{ $order->created_at->format('d M Y, h:i A') }}</span></div>
            </div>
            <div class="text-center mt-4">
                <a href="/customer-dashboard" class="btn" style="background:#1a5276;color:white;border-radius:8px;padding:10px 24px;font-weight:600;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>