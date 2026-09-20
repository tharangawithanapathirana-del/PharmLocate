<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; display: flex; }
        .sidebar { width: 240px; min-height: 100vh; background: #183153; position: fixed; top: 0; left: 0; padding: 24px 0; z-index: 100; }
        .sidebar h4 { color: white; padding: 0 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.7); text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: white; }
        .main { margin-left: 240px; flex: 1; padding: 24px; }
    </style>
</head>
<body>
<div class="sidebar">
    <h4>💊 PharmLocate <span style="font-size:12px;color:#888;">Admin</span></h4>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
    <a href="{{ route('admin.pharmacies') }}" class="{{ request()->routeIs('admin.pharmacies') ? 'active' : '' }}">🏥 Pharmacies</a>
    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">👤 Users</a>
    <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}">🛒 Orders</a>
    <a href="{{ route('admin.health-tips') }}" class="{{ request()->routeIs('admin.health-tips') ? 'active' : '' }}">💡 Health Tips</a>
    <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">📊 Reports</a>
    
    {{-- 🟢 මෙතනට Logout බොත්තම එකතු කරලා --}}
    <div style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding: 16px 20px;">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,0.7);width:100%;text-align:left;padding:0;cursor:pointer;">
                🚪 Log out
            </button>
        </form>
    </div>
</div>

<div class="main">
    @yield('content')
</div>

</body>
</html>