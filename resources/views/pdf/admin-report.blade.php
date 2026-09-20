<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Report - PharmLocate</title>
    <style>
        @page {
            margin: 30px 20px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            background: #183153;
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #5dade2;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
        }
        .section-title {
            color: #183153;
            font-size: 16px;
            font-weight: 700;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1;
            min-width: 120px;
            background: #f4f7fb;
            padding: 12px 16px;
            border-radius: 8px;
            text-align: center;
        }
        .summary-box .num {
            font-size: 20px;
            font-weight: 800;
            color: #183153;
        }
        .summary-box .lbl {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }
        th {
            background: #183153;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f4f8;
        }
        tr:nth-child(even) {
            background: #fafcff;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #aaa;
            font-size: 10px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>📊 PharmLocate Admin Report</h1>
        <p>Generated on {{ $generatedAt }}</p>
    </div>

    <!-- Summary -->
    <h4 class="section-title">📈 System Overview</h4>
    <div class="summary-grid">
        <div class="summary-box">
            <div class="num">{{ $totalUsers }}</div>
            <div class="lbl">Users</div>
        </div>
        <div class="summary-box">
            <div class="num">{{ $totalPharmacies }}</div>
            <div class="lbl">Pharmacies</div>
        </div>
        <div class="summary-box">
            <div class="num">{{ $totalMedicines }}</div>
            <div class="lbl">Medicines</div>
        </div>
        <div class="summary-box">
            <div class="num">{{ $totalOrders }}</div>
            <div class="lbl">Orders</div>
        </div>
        <div class="summary-box">
            <div class="num">LKR {{ number_format($totalRevenue, 2) }}</div>
            <div class="lbl">Total Revenue</div>
        </div>
    </div>

    <!-- Pending Pharmacies -->
    <h4 class="section-title">⏳ Pending Approvals</h4>
    @if($pendingPharmacies->count() > 0)
        <table>
            <thead><tr><th>Pharmacy</th><th>Email</th></tr></thead>
            <tbody>
                @foreach($pendingPharmacies as $p)
                <tr><td><strong>{{ $p->name }}</strong></td><td>{{ $p->user->email }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color:#27ae60; font-weight:600;">✅ All pharmacies are approved.</p>
    @endif

    <!-- Recent Orders -->
    <h4 class="section-title">🛒 Recent Orders</h4>
    @if($recentOrders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Order ID</th><th>Customer</th><th>Pharmacy</th>
                    <th>Medicine</th><th>Qty</th><th>Amount</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>#PL-{{ sprintf('%04d', $order->id) }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->pharmacy->name ?? 'N/A' }}</td>
                    <td>{{ $order->items->first()->medicine->name ?? 'N/A' }}</td>
                    <td>{{ $order->items->first()->quantity ?? 0 }}</td>
                    <td>LKR {{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color:#888;">No recent orders.</p>
    @endif

    <div class="footer">
        Generated by PharmLocate · {{ $generatedAt }}
    </div>

</body>
</html>