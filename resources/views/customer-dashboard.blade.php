<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }

        .navbar { background: rgba(15,30,50,0.97); backdrop-filter: blur(10px); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; text-decoration: none; }
        .nav-pill { color: rgba(255,255,255,0.7) !important; font-size: 14px; padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.15); margin-left: 6px; text-decoration: none; transition: all .2s; cursor: pointer; background: none; }
        .nav-pill:hover { background: rgba(255,255,255,0.1); color: white !important; }
        .nav-pill.active { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-pill-danger { background: rgba(231,76,60,0.2) !important; border-color: rgba(231,76,60,0.4) !important; color: #ff8a80 !important; }
        .nav-pill-danger:hover { background: rgba(231,76,60,0.35) !important; }

        .welcome-bar { background: #1a5276; padding: 10px 0; text-align: center; width: 100%; }
        .welcome-bar span { color: rgba(255,255,255,0.85); font-size: 13px; }
        .welcome-bar strong { color: #5dade2; }

        .page-header { background: linear-gradient(135deg, #0f1e32, #1a5276); padding: 28px 0; width: 100%; }

        /* Tabs */
        .tab-nav { display: flex; gap: 4px; background: white; border-radius: 12px; padding: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .tab-btn { flex: 1; padding: 10px; border: none; background: none; border-radius: 8px; font-size: 14px; font-weight: 500; color: #888; cursor: pointer; transition: all .2s; }
        .tab-btn.active { background: #1a5276; color: white; }
        .tab-btn:hover:not(.active) { background: #f0f4f8; color: #1a5276; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

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
        .badge-processing { background: #e6f1fb; color: #0c447c; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-delivered { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }

        .quick-btn { background: white; border: 1.5px solid #e0e8f0; border-radius: 14px; padding: 20px; text-align: center; text-decoration: none; transition: all .2s; display: block; }
        .quick-btn:hover { border-color: #2980b9; background: #e6f1fb; transform: translateY(-2px); }
        .quick-btn .icon { font-size: 32px; margin-bottom: 8px; }
        .quick-btn .label { font-size: 13px; font-weight: 600; color: #1a5276; }

        /* Health tips */
        .tip-card { background: white; border-radius: 16px; border: none; box-shadow: 0 2px 16px rgba(0,0,0,0.06); transition: all .25s; overflow: hidden; }
        .tip-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .tip-icon { width: 100%; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 36px; }
        .tip-form { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .form-control { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .btn-submit { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; border-radius: 10px; padding: 12px 28px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(41,128,185,0.3); }

        /* Profile */
        .profile-avatar { width: 80px; height: 80px; background: #e6f1fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 12px; }
        .profile-row { display: flex; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #f0f4f8; font-size: 14px; }
        .profile-row:last-child { border-bottom: none; }
    </style>
</head>
<body>

<div class="d-flex flex-column min-vh-100">

    <div class="welcome-bar">
        <span>👋 Welcome back, <strong>{{ auth()->user()->name }}</strong>!</span>
    </div>

    <nav class="navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/customer-dashboard">💊 PharmLocate</a>
            <div class="d-flex align-items-center">
                <button class="nav-pill active" onclick="showTab('dashboard',this)">📊 Dashboard</button>
                <button class="nav-pill" onclick="showTab('orders',this)">🛒 My orders</button>
                <button class="nav-pill" onclick="showTab('tips',this)">💡 Health tips</button>
                <button class="nav-pill" onclick="showTab('profile',this)">👤 My profile</button>
                <a href="/search" class="nav-pill">🔍 Search</a>
                <form method="POST" action="/logout" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="nav-pill nav-pill-danger">🚪 Log out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="page-header text-white">
        <div class="container">
            <h4 class="fw-bold mb-1" id="page-title">My Dashboard</h4>
            <p class="opacity-75 small mb-0" id="page-sub">Manage your orders, prescriptions and health tips</p>
        </div>
    </div>

    <div class="container py-4 flex-grow-1">

        <!-- 🟢 DASHBOARD TAB -->
        <div id="tab-dashboard" class="tab-content active">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#e6f1fb">🛒</div>
                        <div>
                            <div class="stat-num" style="color:#1a5276">{{ $orders->count() }}</div>
                            <div class="stat-lbl">Total orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#faeeda">⏳</div>
                        <div>
                            <div class="stat-num" style="color:#854f0b">{{ $orders->where('status', 'pending')->count() }}</div>
                            <div class="stat-lbl">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#eaf3de">✅</div>
                        <div>
                            <div class="stat-num" style="color:#27500a">{{ $orders->where('status', 'delivered')->count() }}</div>
                            <div class="stat-lbl">Delivered</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <a href="/search" class="quick-btn"><div class="icon">🔍</div><div class="label">Search medicine</div></a>
                </div>
                <div class="col-md-3">
                    <a href="/order" class="quick-btn"><div class="icon">🛒</div><div class="label">Place order</div></a>
                </div>
                <div class="col-md-3">
                    <button class="quick-btn w-100" onclick="showTab('tips',document.querySelector('[onclick*=tips]'))">
                        <div class="icon">💡</div><div class="label">Health tips</div>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="quick-btn w-100" onclick="showTab('profile',document.querySelector('[onclick*=profile]'))">
                        <div class="icon">👤</div><div class="label">My profile</div>
                    </button>
                </div>
            </div>

            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Recent orders</h6>
                    <button onclick="showTab('orders',document.querySelector('[onclick*=orders]'))" style="font-size:13px;color:#2980b9;font-weight:600;background:none;border:none;cursor:pointer">View all →</button>
                </div>
                <table class="orders-table">
                    <thead><tr><th>Order ID</th><th>Medicine</th><th>Pharmacy</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($orders->take(3) as $order)
                        <tr>
                            <td><span style="font-family:monospace;color:#2980b9;font-weight:600">#PL-{{ sprintf('%04d', $order->id) }}</span></td>
                            <td>{{ $order->items->first()->medicine->name ?? 'N/A' }} ×{{ $order->items->first()->quantity ?? 0 }}</td>
                            <td>{{ $order->pharmacy->name ?? 'N/A' }}</td>
                            <td>LKR {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge-delivered">Delivered</span>
                                @else
                                    <span class="badge-processing">Processing</span>
                                @endif
                            </td>
                            <td>
                                {{-- 🟢 මෙතන Action බොත්තම් හරියට Update කරලා --}}
                                @if($order->status == 'pending')
                                    <a href="#" style="font-size:12px;color:#c0392b;font-weight:600">Cancel</a>
                                @elseif($order->status == 'delivered')
                                    <a href="/order-detail?id={{ $order->id }}" style="font-size:12px;color:#2980b9;font-weight:600;text-decoration:none;">View →</a>
                                @else
                                    <a href="/order-detail?id={{ $order->id }}" style="font-size:12px;color:#2980b9;font-weight:600;text-decoration:none;">Track →</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 🟢 ORDERS TAB -->
        <div id="tab-orders" class="tab-content">
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">My orders</h6>
                    <a href="/order" class="btn btn-primary btn-sm" style="background:#1a5276;border:none;border-radius:8px">+ New order</a>
                </div>
                <table class="orders-table">
                    <thead><tr><th>Order ID</th><th>Medicine</th><th>Pharmacy</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><span style="font-family:monospace;color:#2980b9;font-weight:600">#PL-{{ sprintf('%04d', $order->id) }}</span></td>
                            <td>{{ $order->items->first()->medicine->name ?? 'N/A' }} ×{{ $order->items->first()->quantity ?? 0 }}</td>
                            <td>{{ $order->pharmacy->name ?? 'N/A' }}</td>
                            <td>LKR {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge-delivered">Delivered</span>
                                @else
                                    <span class="badge-processing">Processing</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    {{-- Cancel Button --}}
                                    <form action="{{ route('order.cancel', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="font-size:12px;color:#c0392b;font-weight:600;background:none;border:none;cursor:pointer;">Cancel</button>
                                    </form>
                                @elseif($order->status == 'delivered')
                                    {{-- View Button --}}
                                    <a href="/order-detail?id={{ $order->id }}" style="font-size:12px;color:#2980b9;font-weight:600;text-decoration:none;">View →</a>
                                @else
                                    {{-- Track Button (Processing/Dispatched) --}}
                                    <a href="/order-detail?id={{ $order->id }}" style="font-size:12px;color:#2980b9;font-weight:600;text-decoration:none;">Track →</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 🟢 HEALTH TIPS TAB -->
        <div id="tab-tips" class="tab-content">
            <form method="POST" action="/health-tips" enctype="multipart/form-data">
                @csrf
                <div class="tip-form mb-4">
                    <h6 class="fw-bold mb-3">➕ Share a health tip</h6>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted" style="font-size:13px">Tip title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Benefits of drinking warm water in the morning" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted" style="font-size:13px">Category</label>
                            <select name="category" class="form-control">
                                <option>Cardio</option>
                                <option>Nutrition</option>
                                <option>Fitness</option>
                                <option>Mental health</option>
                                <option>General</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted" style="font-size:13px">Description</label>
                            <textarea name="content" class="form-control" rows="3" placeholder="Share your health tip details here..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted" style="font-size:13px">Image (optional)</label>
                            <div class="upload-area" onclick="document.getElementById('tip-image-dash').click()" style="border:1.5px dashed #b5d4f4;border-radius:10px;padding:14px;text-align:center;cursor:pointer;background:#f8fbff;transition:all .2s">
                                <input type="file" id="tip-image-dash" name="image" accept="image/*" style="display:none" onchange="showDashImageName(this)">
                                <div id="dash-upload-placeholder">
                                    <div style="font-size:24px;margin-bottom:4px">📷</div>
                                    <div style="font-size:13px;color:#2980b9;font-weight:600">Click to upload image</div>
                                    <small class="text-muted">JPG, PNG — Max 2MB</small>
                                </div>
                                <div id="dash-upload-done" class="d-none">
                                    <div style="font-size:24px;margin-bottom:4px">✅</div>
                                    <div style="font-size:13px;color:#27500a;font-weight:600" id="dash-image-name"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-submit">✅ Submit tip</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">💡 Health tips</h6>
                <a href="/health-tips" class="btn btn-sm fw-bold" style="background:#1a5276;color:white;border:none;border-radius:8px;padding:6px 16px">
                    See all tips →
                </a>
            </div>

            {{-- Database එකෙන් එන Health Tips පෙන්වීම --}}
            <div class="row g-4">
                @forelse($healthTips as $tip)
                <div class="col-md-4">
                    <div class="tip-card">
                        {{-- 🟢 Image එක තියෙනවා නම් පෙන්වන්න, නැත්නම් 💡 අයිකනය පෙන්වන්න --}}
                        @if($tip->image)
                            <div class="tip-icon" style="background-image: url('{{ asset('storage/' . $tip->image) }}'); background-size: cover; background-position: center; height: 80px; width: 100%;"></div>
                        @else
                            <div class="tip-icon" style="background:#e6f1fb">💡</div>
                        @endif
                        <div class="p-3">
                            <span class="badge bg-primary mb-2" style="font-size:11px">{{ $tip->category ?? 'General' }}</span>
                            <h6 class="fw-bold" style="font-size:14px">{{ $tip->title }}</h6>
                            <p class="text-muted" style="font-size:13px">{{ \Illuminate\Support\Str::limit($tip->content, 80) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-4">
                    <p>No health tips shared yet. Be the first to share!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 🟢 PROFILE TAB -->
        <div id="tab-profile" class="tab-content">
            @if(session('profile_success'))
                <div class="alert mb-4" style="background:#eaf3de;border:none;border-radius:10px;color:#27500a;padding:12px 16px">
                    ✅ {{ session('profile_success') }}
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="section-card text-center">
                        <div class="profile-avatar">👤</div>
                        <h5 class="fw-bold">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small">{{ auth()->user()->email }}</p>
                        <span style="background:#eaf3de;color:#27500a;font-size:12px;padding:4px 14px;border-radius:20px;font-weight:600">✅ Customer</span>
                        <div class="mt-3 text-muted" style="font-size:12px">
                            Member since {{ auth()->user()->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="fw-bold mb-4">✏️ Edit profile</h6>
                        <form method="POST" action="/profile/update">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Full name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ auth()->user()->name }}" required>
                                @error('name')
                                    <div class="invalid-feedback">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Email address</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled style="background:#f4f7fb;color:#888">
                                <small class="text-muted">Email cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Phone number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ auth()->user()->phone ?? '' }}" placeholder="071-234-5678">
                                @error('phone')
                                    <div class="invalid-feedback">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="No 12, Galle Rd, Colombo 03">{{ auth()->user()->address ?? '' }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">⚠️ {{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit">💾 Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer class="mt-auto" style="background:#0f1e32;color:rgba(255,255,255,0.6);padding:24px 0;">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="fw-bold text-white">💊 PharmLocate</span>
            <small>© 2026 PharmLocate. All rights reserved.</small>
        </div>
    </footer>

</div>

<script>
const titles = {
    dashboard: ['My Dashboard', 'Manage your orders and health information'],
    orders: ['My Orders', 'Track and manage all your medicine orders'],
    tips: ['Health Tips', 'Browse and share health tips with the community'],
    profile: ['My Profile', 'View and update your personal information'],
};

function showTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.nav-pill').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    if (btn) btn.classList.add('active');
    document.getElementById('page-title').textContent = titles[tab][0];
    document.getElementById('page-sub').textContent = titles[tab][1];
}

function showDashImageName(input) {
    if (input.files && input.files[0]) {
        document.getElementById('dash-upload-placeholder').classList.add('d-none');
        document.getElementById('dash-upload-done').classList.remove('d-none');
        document.getElementById('dash-image-name').textContent = input.files[0].name;
    }
}
</script>
</body>
</html>