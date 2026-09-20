<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Detail — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }

        .navbar { background: rgba(15,30,50,0.97); backdrop-filter: blur(10px); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; }
        .nav-pill { color: rgba(255,255,255,0.7) !important; font-size: 14px; padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.15); margin-left: 6px; transition: all .2s; }
        .nav-pill:hover { background: rgba(255,255,255,0.1); color: white !important; }
        .btn-back { background: rgba(255,255,255,0.1); color: white !important; border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 6px 16px; font-size: 13px; transition: all .2s; text-decoration: none; }
        .btn-back:hover { background: rgba(255,255,255,0.2); }

        .medicine-hero { background: linear-gradient(135deg, #0f1e32, #1a5276); padding: 50px 0 30px; }
        .med-icon-wrap { width: 110px; height: 110px; background: rgba(255,255,255,0.12); border-radius: 28px; display: flex; align-items: center; justify-content: center; font-size: 56px; margin: 0 auto 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        .rx-badge { background: #eaf3de; color: #27500a; font-size: 12px; padding: 4px 14px; border-radius: 20px; font-weight: 600; display: inline-block; }
        .rx-badge-required { background: #faeeda; color: #633806; }

        .detail-card { background: white; border-radius: 20px; border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; }
        .price-display { font-size: 2.4rem; font-weight: 800; color: #1a5276; line-height: 1; }
        .stock-badge { background: #eaf3de; color: #27500a; padding: 6px 18px; border-radius: 20px; font-weight: 700; font-size: 14px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f0f4f8; }
        .info-row:last-child { border-bottom: none; }
        .info-key { color: #888; font-size: 14px; }
        .info-val { font-weight: 600; color: #1a3a4f; font-size: 14px; }

        .pharmacy-card { background: linear-gradient(135deg, #e6f1fb, #f0f7fd); border-radius: 16px; padding: 20px; border: 1.5px solid #b5d4f4; }
        .ph-avatar-lg { width: 56px; height: 56px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .open-badge { background: #eaf3de; color: #27500a; font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
        .dist-badge { background: white; color: #0c447c; font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 700; box-shadow: 0 1px 6px rgba(0,0,0,0.08); }

        .btn-order { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; border-radius: 12px; padding: 16px; font-size: 16px; font-weight: 700; color: white; width: 100%; transition: all .2s; }
        .btn-order:hover { background: linear-gradient(135deg, #154360, #2471a3); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(41,128,185,0.35); color: white; }
        .btn-mapview { background: white; border: 1.5px solid #2980b9; border-radius: 12px; padding: 16px; font-size: 15px; font-weight: 600; color: #2980b9; width: 100%; transition: all .2s; }
        .btn-mapview:hover { background: #e6f1fb; }

        .related-card { background: white; border-radius: 14px; border: 1.5px solid #e8f0fe; padding: 16px; transition: all .2s; }
        .related-card:hover { border-color: #2980b9; box-shadow: 0 4px 16px rgba(41,128,185,0.1); }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="/">💊 PharmLocate</a>
        
        {{-- 🟢 Back button එකට `q` (search query) එක යවන විදියට වෙනස් කරලා --}}
        <a href="/search?q={{ urlencode(request()->query('q', '')) }}" class="btn-back">← Back to results</a>

        <div class="d-flex">
            @auth
                @if(auth()->user()->role === 'pharmacy')
                    <a href="/pharmacy-dashboard" class="nav-pill">📊 Dashboard</a>
                @else
                    <a href="/customer-dashboard" class="nav-pill">📊 Dashboard</a>
                @endif
                <form method="POST" action="/logout" class="d-inline ms-1">
                    @csrf
                    <button type="submit" class="nav-pill" style="border:none;cursor:pointer;background:rgba(231,76,60,0.2);border-color:rgba(231,76,60,0.4);color:#ff8a80">🚪 Log out</button>
                </form>
            @else
                <a href="/login" class="nav-pill">Log in</a>
                <a href="/register" class="nav-pill" style="background:#2980b9;border-color:#2980b9;color:white!important">Register</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Medicine hero banner -->
<div class="medicine-hero text-center text-white">
    <div class="container">
        <div class="med-icon-wrap">💊</div>
        <h2 class="fw-bold mb-1">{{ $medicine->name }}</h2>
        <p class="opacity-75 mb-3">
            Generic name: {{ $medicine->generic_name ?? 'N/A' }} &nbsp;|&nbsp; Category: {{ $medicine->category ?? 'N/A' }}
        </p>
        <span class="rx-badge {{ $medicine->is_rx_required ? 'rx-badge-required' : '' }}">
            {{ $medicine->is_rx_required ? '⚠️ Prescription required' : '✅ No prescription required' }}
        </span>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        <!-- Left: Details -->
        <div class="col-lg-8">

            <!-- Price & stock -->
            <div class="detail-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <div class="price-display">LKR {{ number_format($medicine->price, 2) }}</div>
                        <small class="text-muted">per tablet</small>
                    </div>
                    <span class="stock-badge">
                        {{ $medicine->quantity > 0 ? '✅ In stock' : '❌ Out of stock' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-key">Available quantity</span>
                    <span class="info-val">{{ $medicine->quantity }} tablets</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Expiry date</span>
                    <span class="info-val">{{ $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date)->format('F Y') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Dosage form</span>
                    <span class="info-val">{{ $medicine->dosage_form ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Strength</span>
                    <span class="info-val">{{ $medicine->strength ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Manufacturer</span>
                    <span class="info-val">{{ $medicine->manufacturer ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Prescription required</span>
                    <span class="info-val" style="color:{{ $medicine->is_rx_required ? '#633806' : '#27500a' }}">
                        {{ $medicine->is_rx_required ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="mt-3 pt-2">
                    <span class="info-key d-block mb-2">Description</span>
                    <p class="text-muted" style="font-size:14px;line-height:1.7">
                        {{ $medicine->description ?? 'No description available.' }}
                    </p>
                </div>
            </div>

            <!-- Pharmacy info -->
            <div class="detail-card p-4">
                <h6 class="fw-bold mb-3">🏥 Available at</h6>
                <div class="pharmacy-card" id="pharmacyCard" data-lat="{{ $medicine->pharmacy->latitude ?? '' }}" data-lng="{{ $medicine->pharmacy->longitude ?? '' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="ph-avatar-lg">🏥</div>
                            <div>
                                <div class="fw-bold" style="color:#1a5276">{{ $medicine->pharmacy->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $medicine->pharmacy->address ?? 'N/A' }}</small><br>
                                <small class="text-muted">📞 {{ $medicine->pharmacy->phone ?? 'N/A' }} &nbsp;|&nbsp; 📧 {{ $medicine->pharmacy->email ?? 'N/A' }}</small><br>
                                <div class="mt-1 d-flex gap-2">
                                    <span class="open-badge">⏰ {{ $medicine->pharmacy->opening_hours ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="dist-badge" id="distanceDisplay">📍 Calculating...</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Order panel -->
        <div class="col-lg-4">
            <div class="detail-card p-4 mb-4" style="position:sticky;top:80px">
                <h6 class="fw-bold mb-3">Order this medicine</h6>

                <div class="d-flex justify-content-between mb-3 p-3" style="background:#f4f7fb;border-radius:10px">
                    <span class="text-muted small">Price per tablet</span>
                    <span class="fw-bold text-primary">LKR {{ number_format($medicine->price, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4 p-3" style="background:#f4f7fb;border-radius:10px">
                    <span class="text-muted small">Stock available</span>
                    <span class="fw-bold" style="color:#27500a">{{ $medicine->quantity }} tablets</span>
                </div>

                <a href="/order?id={{ $medicine->id }}&q={{ urlencode(request()->query('q', '')) }}" class="btn-order d-block text-center text-decoration-none mb-3">
                    🛒 Order now
                </a>
                <a href="/search?q={{ urlencode(request()->query('q', '')) }}" class="btn-mapview">
                    🗺️ View on map
                </a>

                <div class="mt-3 p-3" style="background:#faeeda;border-radius:10px">
                    <small style="color:#633806">
                        ⚡ <strong>Fast delivery</strong> — order before 6:00 PM for same-day delivery in Colombo area.
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

<footer style="background:#0f1e32;color:rgba(255,255,255,0.6);padding:24px 0;margin-top:30px">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold text-white">💊 PharmLocate</span>
        <small>© 2026 PharmLocate. All rights reserved.</small>
    </div>
</footer>

<!-- 🟢 Distance Calculation JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const card = document.getElementById('pharmacyCard');
    const distDisplay = document.getElementById('distanceDisplay');

    if (card && distDisplay) {
        const pharmLat = parseFloat(card.dataset.lat);
        const pharmLng = parseFloat(card.dataset.lng);

        if (pharmLat && pharmLng) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const userLat = pos.coords.latitude;
                    const userLng = pos.coords.longitude;
                    const distance = getDistanceFromLatLonInKm(userLat, userLng, pharmLat, pharmLng);
                    distDisplay.textContent = '📍 ' + distance.toFixed(1) + ' km';
                }, function(error) {
                    distDisplay.textContent = '📍 Location denied';
                });
            } else {
                distDisplay.textContent = '📍 No GPS support';
            }
        } else {
            distDisplay.textContent = '📍 No location';
        }
    }

    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2 - lat1);
        var dLon = deg2rad(lon2 - lon1);
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }
});
</script>
</body>
</html>