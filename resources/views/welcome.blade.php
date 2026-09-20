<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        .navbar { background: rgba(15, 30, 50, 0.95); backdrop-filter: blur(10px); padding: 16px 0; }
        .navbar-brand { font-size: 22px; font-weight: 700; color: white !important; letter-spacing: -0.5px; }
        .nav-pill { background: rgba(255,255,255,0.1); color: white !important; border-radius: 20px; padding: 6px 16px; font-size: 14px; margin-left: 6px; border: 1px solid rgba(255,255,255,0.2); transition: all .2s; }
        .nav-pill:hover { background: rgba(255,255,255,0.2); }
        .nav-pill-primary { background: #2980b9; border-color: #2980b9; color: white !important; }
        .nav-pill-primary:hover { background: #2471a3; }
        .nav-pill-danger { background: rgba(231,76,60,0.2) !important; border-color: rgba(231,76,60,0.4) !important; color: #ff8a80 !important; }

        .hero {
            background: linear-gradient(135deg, #0f1e32 0%, #1a5276 50%, #2980b9 100%);
            min-height: 88vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: rgba(41,128,185,0.15);
            border-radius: 50%;
            top: -100px; right: -100px;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(26,82,118,0.2);
            border-radius: 50%;
            bottom: -80px; left: -80px;
        }
        .hero-content { position: relative; z-index: 2; }
        .hero h1 { font-size: 3.2rem; font-weight: 800; color: white; line-height: 1.2; }
        .hero h1 span { color: #5dade2; }
        .hero p { color: rgba(255,255,255,0.75); font-size: 1.1rem; }
        .search-wrap { background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); border-radius: 16px; padding: 24px; max-width: 580px; }
        .search-wrap input { border-radius: 10px; border: none; padding: 14px 18px; font-size: 15px; }
        .search-wrap input:focus { box-shadow: 0 0 0 3px rgba(41,128,185,0.3); }
        .btn-search { background: #e67e22; border: none; border-radius: 10px; padding: 14px 28px; font-weight: 700; font-size: 15px; color: white; transition: all .2s; }
        .btn-search:hover { background: #d35400; transform: translateY(-1px); }

        .stats-bar { background: white; padding: 24px 0; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .stat-item { text-align: center; border-right: 1px solid #f0f0f0; }
        .stat-item:last-child { border-right: none; }
        .stat-num { font-size: 1.8rem; font-weight: 800; color: #1a5276; }
        .stat-lbl { font-size: 13px; color: #888; }

        .tips-section { background: #f8fafd; padding: 70px 0; }
        .section-title { font-size: 1.8rem; font-weight: 700; color: #1a5276; }
        .section-sub { color: #888; font-size: 15px; }
        .tip-card { background: white; border-radius: 16px; border: none; box-shadow: 0 2px 16px rgba(0,0,0,0.06); transition: all .25s; overflow: hidden; }
        .tip-card:hover { transform: translateY(-6px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
        .tip-icon { width: 100%; height: 90px; display: flex; align-items: center; justify-content: center; font-size: 40px; }
        .tip-card .card-body { padding: 20px; }

        .pharmacies-section { background: white; padding: 70px 0; }
        .ph-card { border-radius: 14px; border: 1.5px solid #e8f4fb; padding: 20px; transition: all .2s; }
        .ph-card:hover { border-color: #2980b9; box-shadow: 0 4px 20px rgba(41,128,185,0.12); }
        .ph-avatar { width: 50px; height: 50px; background: #e6f1fb; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .dist-badge { background: #eaf3de; color: #27500a; font-size: 12px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }

        .cta-section { background: linear-gradient(135deg, #1a5276, #2980b9); padding: 70px 0; }

        footer { background: #0f1e32; color: rgba(255,255,255,0.6); padding: 30px 0; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">💊 PharmLocate</a>
        <div class="ms-auto d-flex align-items-center">
            <a href="#tips" class="nav-pill">Health tips</a>
            <a href="#pharmacies" class="nav-pill">Pharmacies</a>
            @auth
                @if(auth()->user()->role === 'pharmacy')
                    <a href="/pharmacy-dashboard" class="nav-pill">📊 Dashboard</a>
                @else
                    <a href="/customer-dashboard" class="nav-pill">📊 Dashboard</a>
                @endif
                <form method="POST" action="/logout" class="d-inline ms-1">
                    @csrf
                    <button type="submit" class="nav-pill nav-pill-danger" style="cursor:pointer;border:none">🚪 Log out</button>
                </form>
            @else
                <a href="/login" class="nav-pill">Log in</a>
                <a href="/register" class="nav-pill nav-pill-primary">Register</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero" style="padding-top:80px">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-content">
                <div class="mb-3">
                    <span style="background:rgba(93,173,226,0.2);color:#5dade2;padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600">Sri Lanka Private Pharmacy Association</span>
                </div>
                <h1>Find your <span>medicine</span>,<br>find your pharmacy</h1>
                <p class="mt-3 mb-4">Search by medicine name to instantly locate nearby pharmacies stocking what you need — with prices, stock levels, and directions.</p>
                <div class="search-wrap">
                    <form action="/search" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" id="home-search"
                                class="form-control form-control-lg"
                                placeholder="e.g. Paracetamol, Amoxicillin..."
                                autocomplete="off" required>
                            <button type="submit" class="btn-search ms-2">🔍 Search</button>
                        </div>
                    </form>
                    <div class="mt-2 d-flex gap-2 flex-wrap">
                        <span style="color:rgba(255,255,255,0.5);font-size:12px">Popular:</span>
                        <span style="color:#5dade2;font-size:12px;cursor:pointer" onclick="quickSearch('Paracetamol')">Paracetamol</span>
                        <span style="color:#5dade2;font-size:12px;cursor:pointer" onclick="quickSearch('Amoxicillin')">Amoxicillin</span>
                        <span style="color:#5dade2;font-size:12px;cursor:pointer" onclick="quickSearch('Insulin')">Insulin</span>
                        <span style="color:#5dade2;font-size:12px;cursor:pointer" onclick="quickSearch('Metformin')">Metformin</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-content text-center d-none d-lg-block">
                <div style="font-size:160px;line-height:1;filter:drop-shadow(0 20px 40px rgba(0,0,0,0.3))">💊</div>
            </div>
        </div>
    </div>
</section>

<!-- Stats bar -->
<div class="stats-bar">
    <div class="container">
        <div class="row">
            <div class="col-3 stat-item">
                <div class="stat-num">250+</div>
                <div class="stat-lbl">Registered pharmacies</div>
            </div>
            <div class="col-3 stat-item">
                <div class="stat-num">5,000+</div>
                <div class="stat-lbl">Medicines listed</div>
            </div>
            <div class="col-3 stat-item">
                <div class="stat-num">10,000+</div>
                <div class="stat-lbl">Happy customers</div>
            </div>
            <div class="col-3 stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-lbl">Available online</div>
            </div>
        </div>
    </div>
</div>

<!-- Health Tips (Database එකෙන් එන කොටස) -->
<section class="tips-section" id="tips">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title">Health tips</div>
            <div class="section-sub mt-2">Stay informed with the latest wellness advice</div>
        </div>
        <div class="row g-4">
            {{-- 🟢 අලුතින් එකතු කරපු Database Loop එක --}}
            @forelse($healthTips as $tip)
                <div class="col-md-4">
                    <div class="tip-card">
                        {{-- Image එක තියෙනවා නම් පෙන්වන්න --}}
                        @if($tip->image)
                            <div class="tip-icon" style="background-image: url('{{ asset('storage/' . $tip->image) }}'); background-size: cover; background-position: center; height: 90px; width: 100%;"></div>
                        @else
                            {{-- Image එක නැත්නම් Category එකට අනුව අයිකනයක් පෙන්වන්න --}}
                            <div class="tip-icon" style="background:
                                @if($tip->category == 'Cardio') #e6f1fb
                                @elseif($tip->category == 'Nutrition') #eaf3de
                                @elseif($tip->category == 'Fitness') #faeeda
                                @elseif($tip->category == 'Mental health') #f3e6fb
                                @else #f4f7fb @endif">
                                @if($tip->category == 'Cardio') ❤️
                                @elseif($tip->category == 'Nutrition') 🥦
                                @elseif($tip->category == 'Fitness') 🏃
                                @elseif($tip->category == 'Mental health') 🧠
                                @else 💡 @endif
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge rounded-pill mb-2" style="background:
                                @if($tip->category == 'Cardio') #e6f1fb; color:#0c447c;
                                @elseif($tip->category == 'Nutrition') #eaf3de; color:#27500a;
                                @elseif($tip->category == 'Fitness') #faeeda; color:#633806;
                                @elseif($tip->category == 'Mental health') #f3e6fb; color:#6c3483;
                                @else #f4f7fb; color:#555 @endif">
                                {{ $tip->category ?? 'General' }}
                            </span>
                            <h6 class="fw-bold">{{ $tip->title }}</h6>
                            <p class="text-muted small">{{ \Illuminate\Support\Str::limit($tip->content, 80) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No health tips available yet.</p>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="/health-tips" class="btn fw-bold px-5 py-2 rounded-pill" style="background:#1a5276;color:white;border:none">
                💡 See all health tips →
            </a>
        </div>
    </div>
</section>

<!-- Featured Pharmacies -->
<section class="pharmacies-section" id="pharmacies">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title">Featured pharmacies</div>
            <div class="section-sub mt-2">Trusted pharmacies near you</div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="ph-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="ph-avatar">🏥</div>
                        <div>
                            <div class="fw-bold">MediCare Pharmacy</div>
                            <small class="text-muted">Colombo 03</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">📞 011-234-5678</small>
                        <span class="dist-badge">📍 0.8 km</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ph-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="ph-avatar">🏥</div>
                        <div>
                            <div class="fw-bold">City Drug Store</div>
                            <small class="text-muted">Colombo 07</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">📞 011-876-5432</small>
                        <span class="dist-badge">📍 1.4 km</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ph-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="ph-avatar">🏥</div>
                        <div>
                            <div class="fw-bold">Sunrise Pharmacy</div>
                            <small class="text-muted">Nugegoda</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">📞 011-999-1111</small>
                        <span class="dist-badge">📍 3.2 km</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <h2 class="fw-bold text-white mb-3">Are you a pharmacy owner?</h2>
        <p class="text-white opacity-75 mb-4">Register your pharmacy on PharmLocate and reach thousands of customers across Sri Lanka.</p>
        <a href="/register" class="btn btn-light fw-bold px-5 py-3 rounded-pill">Register your pharmacy →</a>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="fw-bold text-white mb-1">💊 PharmLocate</div>
                <small>Sri Lanka Private Pharmacy Association</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>© 2026 PharmLocate. All rights reserved.</small>
            </div>
        </div>
    </div>
</footer>

<script>
function quickSearch(medicine) {
    window.location.href = '/search?q=' + encodeURIComponent(medicine);
}
</script>
</body>
</html>