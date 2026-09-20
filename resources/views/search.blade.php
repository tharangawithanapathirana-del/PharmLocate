<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }
        .navbar { background: rgba(15,30,50,0.97); backdrop-filter: blur(10px); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; text-decoration: none; }
        .search-input { border-radius: 10px 0 0 10px; border: none; padding: 12px 18px; font-size: 15px; min-width: 280px; }
        .search-input:focus { box-shadow: none; outline: none; }
        .btn-search { background: #e67e22; border: none; border-radius: 0 10px 10px 0; padding: 12px 24px; font-weight: 700; color: white; cursor: pointer; }
        .btn-search:hover { background: #d35400; }
        .nav-pill { color: rgba(255,255,255,0.7) !important; font-size: 14px; padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.15); margin-left: 6px; transition: all .2s; text-decoration: none; cursor: pointer; background: none; }
        .nav-pill:hover { background: rgba(255,255,255,0.1); color: white !important; }
        .nav-pill-primary { background: #2980b9; border-color: #2980b9; color: white !important; }
        .nav-pill-danger { background: rgba(231,76,60,0.2) !important; border-color: rgba(231,76,60,0.4) !important; color: #ff8a80 !important; }

        #map { height: 380px; border-radius: 16px; border: 1.5px solid #b5d4f4; z-index: 1; }
        .map-placeholder { height: 380px; background: linear-gradient(135deg, #cfe2ff, #e6f1fb); border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #b5d4f4; flex-direction: column; gap: 8px; }

        .result-card { background: white; border-radius: 16px; border: 1.5px solid #e8f0fe; box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: all .25s; overflow: hidden; }
        .result-card:hover { border-color: #2980b9; box-shadow: 0 6px 24px rgba(41,128,185,0.15); transform: translateY(-2px); }
        .result-card.highlighted { border-color: #e67e22; box-shadow: 0 6px 24px rgba(230,126,34,0.2); }
        .result-card .card-accent { width: 6px; background: linear-gradient(180deg, #1a5276, #2980b9); flex-shrink: 0; }
        .ph-avatar { width: 52px; height: 52px; background: #e6f1fb; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; }
        .price-pill { background: #e6f1fb; color: #0c447c; font-weight: 700; font-size: 15px; padding: 6px 16px; border-radius: 20px; }
        .dist-pill { background: #eaf3de; color: #27500a; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; }
        .stock-pill { background: #eaf3de; color: #27500a; font-size: 12px; padding: 3px 10px; border-radius: 20px; }
        .rx-pill { background: #eeedfe; color: #3c3489; font-size: 12px; padding: 3px 10px; border-radius: 20px; }

        /* 🟢 CSS Class එක හරියට හදා ගත්තා */
        .btn-detail { background: #1a5276; color: white; border: none; border-radius: 8px; padding: 8px 20px; font-size: 13px; font-weight: 600; transition: all .2s; text-decoration: none; display: inline-block; }
        .btn-detail:hover { background: #154360; color: white; }
        .btn-map-view { background: white; color: #2980b9; border: 1.5px solid #2980b9; border-radius: 8px; padding: 8px 20px; font-size: 13px; font-weight: 600; transition: all .2s; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-map-view:hover { background: #e6f1fb; }

        .filter-bar { background: white; border-radius: 12px; padding: 14px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .no-results { text-align: center; padding: 60px 20px; background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">💊 PharmLocate</a>
        <form action="/search" method="GET" class="mx-auto">
            <div class="input-group">
                <input type="text" name="q" class="search-input form-control"
                    value="{{ $query }}" placeholder="Search medicine...">
                <button type="submit" class="btn-search">🔍 Search</button>
            </div>
        </form>
        <div class="ms-3 d-flex">
            @auth
                @if(auth()->user()->role === 'pharmacy')
                    <a href="/pharmacy-dashboard" class="nav-pill">📊 Dashboard</a>
                @else
                    <a href="/customer-dashboard" class="nav-pill">📊 Dashboard</a>
                @endif
                <form method="POST" action="/logout" class="d-inline ms-1">
                    @csrf
                    <button type="submit" class="nav-pill nav-pill-danger" style="border:none;cursor:pointer">🚪 Log out</button>
                </form>
            @else
                <a href="/login" class="nav-pill">Log in</a>
                <a href="/register" class="nav-pill nav-pill-primary">Register</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container py-4 flex-grow-1">

    @if($query)

        {{-- MAP --}}
        <div class="mb-4">
            @if($pharmacies->count() > 0)
                <div id="map"></div>
            @else
                <div class="map-placeholder">
                    <div style="font-size:36px">🗺️</div>
                    <span style="font-size:14px;color:#1a5276;font-weight:500">
                        @if($medicines->count() > 0)
                            Pharmacies found but no location data available
                        @else
                            No pharmacies found for this medicine
                        @endif
                    </span>
                </div>
            @endif
        </div>

        {{-- FILTER BAR --}}
        <div class="filter-bar mb-4 d-flex align-items-center gap-3 flex-wrap">
            <span class="text-muted small">
                @if($medicines->count() > 0)
                    <strong>{{ $medicines->pluck('pharmacy_id')->unique()->count() }} pharmacies</strong>
                    found for "<strong style="color:#1a5276">{{ $query }}</strong>"
                @else
                    No results for "<strong>{{ $query }}</strong>"
                @endif
            </span>
        </div>

        {{-- RESULTS --}}
        @if($medicines->count() > 0)
            <div class="d-flex flex-column gap-3" id="resultsList">
                @foreach($medicines->groupBy('pharmacy_id') as $pharmacyId => $pharmacyMedicines)
                    @php $pharmacy = $pharmacyMedicines->first()->pharmacy; @endphp
                    @if($pharmacy)
                    <div class="result-card d-flex" id="card-{{ $pharmacy->id }}">
                        <div class="card-accent"></div>
                        <div class="p-4 flex-grow-1">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="ph-avatar">🏥</div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $pharmacy->name }}</h6>
                                        <div class="text-muted small mb-1">📍 {{ $pharmacy->address }}</div>
                                        <div class="text-muted small mb-2">📞 {{ $pharmacy->phone }}</div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach($pharmacyMedicines as $medicine)
                                                <span class="stock-pill">
                                                    {{ $medicine->name }} —
                                                    @if($medicine->quantity > 0)
                                                        ✅ {{ $medicine->quantity }} in stock
                                                    @else
                                                        ❌ Out of stock
                                                    @endif
                                                </span>
                                                @if($medicine->is_rx_required)
                                                    <span class="rx-pill">Rx required</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    @foreach($pharmacyMedicines as $medicine)
                                        <div class="price-pill mb-1">LKR {{ number_format($medicine->price, 2) }}</div>
                                    @endforeach
                                    @if($pharmacy->latitude && $pharmacy->longitude)
                                        <span class="dist-pill mt-1 d-block" id="dist-{{ $pharmacy->id }}">📍 Calculating...</span>
                                    @else
                                        <span class="dist-pill mt-1 d-block" style="background:#f4f7fb;color:#888">📍 No location</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                @if($pharmacy->latitude && $pharmacy->longitude)
                                    <button class="btn-map-view"
                                        onclick="panToPharmacy({{ $pharmacy->id }}, {{ $pharmacy->latitude }}, {{ $pharmacy->longitude }}, '{{ addslashes($pharmacy->name) }}', '{{ addslashes($pharmacy->address) }}', '{{ $pharmacy->phone }}')">
                                        🗺️ View on map
                                    </button>
                                @endif
                              
                               <a href="/medicine-detail?id={{ $pharmacyMedicines->first()->id }}&q={{ urlencode($query) }}" class="btn-detail">View details →</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="no-results">
                <div style="font-size:48px;margin-bottom:12px">💊</div>
                <h5 class="fw-bold text-muted">No pharmacies found for "{{ $query }}"</h5>
                <p class="text-muted">Try searching with a different medicine name.</p>
                <a href="/" class="btn btn-primary mt-3" style="background:#1a5276;border:none;border-radius:8px">Back to home</a>
            </div>
        @endif

    @else
        <div class="no-results">
            <div style="font-size:48px;margin-bottom:12px">🔍</div>
            <h5 class="fw-bold text-muted">Search for a medicine</h5>
            <p class="text-muted">Enter a medicine name in the search bar above to find nearby pharmacies.</p>
        </div>
    @endif

</div>

<footer style="background:#0f1e32;color:rgba(255,255,255,0.6);padding:24px 0;margin-top:50px">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold text-white">💊 PharmLocate</span>
        <small>© 2026 PharmLocate. All rights reserved.</small>
    </div>
</footer>
</div>

@if($pharmacies->count() > 0)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// 🟢 $pharmaciesJson වෙනුවට @json($pharmacies) පාවිච්චි කරලා, Database එකේ නියම column නම් දාලා තියෙනවා
const pharmacies = @json($pharmacies);

let map, markers = {}, userLat = 6.9271, userLng = 79.8612;

function initMap() {
    // 🟢 lat/lng වෙනුවට latitude/longitude ලෙස වෙනස් කරලා
    const firstPharmacy = pharmacies.find(p => p.latitude && p.longitude);
    const center = firstPharmacy 
        ? [firstPharmacy.latitude, firstPharmacy.longitude] 
        : [userLat, userLng];

    map = L.map('map').setView(center, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const blueIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
    });

    pharmacies.forEach(p => {
        // 🟢 p.lat වෙනුවට p.latitude, p.lng වෙනුවට p.longitude
        if (p.latitude && p.longitude) {
            const marker = L.marker([p.latitude, p.longitude], { icon: blueIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width:180px">
                        <div style="font-weight:700;color:#1a5276;margin-bottom:4px">💊 ${p.name}</div>
                        <div style="font-size:12px;color:#555;margin-bottom:2px">📍 ${p.address}</div>
                        <div style="font-size:12px;color:#555">📞 ${p.phone}</div>
                    </div>
                `);

            markers[p.id] = marker;

            marker.on('click', () => {
                document.querySelectorAll('.result-card').forEach(c => c.classList.remove('highlighted'));
                const card = document.getElementById('card-' + p.id);
                if (card) {
                    card.classList.add('highlighted');
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });

    // Distance එක ගණනය කිරීමට අවශ්‍ය userLat/userLng ආරක්ෂිතව update කිරීම
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;

            const redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
            });

            L.marker([userLat, userLng], { icon: redIcon })
                .addTo(map)
                .bindPopup('<div style="font-weight:600;color:#c0392b">📍 Your location</div>');

            pharmacies.forEach(p => {
                // 🟢 p.lat/lng වෙනුවට p.latitude/longitude පාවිච්චි කළා
                if (p.latitude && p.longitude) {
                    const distKm = getDistanceKm(userLat, userLng, p.latitude, p.longitude);
                    const el = document.getElementById('dist-' + p.id);
                    if (el) el.textContent = '📍 ' + distKm.toFixed(1) + ' km';
                }
            });
        });
    }
}

function panToPharmacy(id, lat, lng, name, address, phone) {
    map.setView([lat, lng], 16, { animate: true });

    if (markers[id]) {
        markers[id].openPopup();
    }

    document.querySelectorAll('.result-card').forEach(c => c.classList.remove('highlighted'));
    const card = document.getElementById('card-' + id);
    if (card) card.classList.add('highlighted');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function getDistanceKm(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng/2) * Math.sin(dLng/2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

initMap();
</script>
@endif

</body>
</html>