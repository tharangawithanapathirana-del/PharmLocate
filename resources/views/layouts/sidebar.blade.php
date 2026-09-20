<!-- resources/views/layouts/sidebar.blade.php -->

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="icon">💊</div>
        <div class="name">PharmLocate</div>
        <div class="sub">{{ auth()->user()->name }}</div>
    </div>

    {{-- 🟢 Active Class සහ Dynamic Badges --}}
    <a class="nav-item {{ request()->is('pharmacy-dashboard') ? 'active' : '' }}" href="/pharmacy-dashboard">
        <span class="nav-icon">📊</span> Dashboard
    </a>
    <a class="nav-item {{ request()->is('stock-management') ? 'active' : '' }}" href="/stock-management">
        <span class="nav-icon">📦</span> Stock management
    </a>
    <a class="nav-item {{ request()->is('pharmacy-orders*') ? 'active' : '' }}" href="/pharmacy-orders">
        <span class="nav-icon">🛒</span> Orders 
        <span class="nav-badge">{{ $totalOrders ?? 0 }}</span>
    </a>
    <a class="nav-item {{ request()->is('prescriptions') ? 'active' : '' }}" href="{{ route('pharmacy.prescriptions') }}">
    <span class="nav-icon">📋</span> Prescriptions
    <span class="nav-badge">{{ $totalPrescriptions ?? 0 }}</span>
    </a>

    <a class="nav-item {{ request()->is('health-tips') ? 'active' : '' }}" href="/health-tips">
        <span class="nav-icon">💡</span> Health tips
    </a>
    <a class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
    <span class="nav-icon">👤</span> Profile
</a>

<a class="nav-item {{ request()->routeIs('pharmacy.reports*') ? 'active' : '' }}" href="{{ route('pharmacy.reports') }}">
    <span class="nav-icon">📊</span> Reports
</a>

    <a class="nav-item {{ request()->is('/') ? 'active' : '' }}" href="/">
        <span class="nav-icon">🏠</span> Home
    </a>

    <div class="sidebar-footer">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" style="display:flex;align-items:center;gap:12px;width:100%;background:none;border:none;color:rgba(255,255,255,0.6);font-size:14px;padding:10px 0;cursor:pointer">
                <span style="font-size:18px;width:24px;text-align:center">🚪</span> Log out
            </button>
        </form>
    </div>
</div>