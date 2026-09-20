<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tips — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }
        .navbar { background: rgba(15,30,50,0.97); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; text-decoration: none; }
        .nav-pill { color: rgba(255,255,255,0.7) !important; font-size: 14px; padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.15); margin-left: 6px; text-decoration: none; transition: all .2s; cursor: pointer; background: none; }
        .nav-pill:hover { background: rgba(255,255,255,0.1); color: white !important; }
        .nav-pill-danger { background: rgba(231,76,60,0.2) !important; border-color: rgba(231,76,60,0.4) !important; color: #ff8a80 !important; }

        .page-header { background: linear-gradient(135deg, #0f1e32, #1a5276); padding: 40px 0; }

        .tip-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.06); transition: all .25s; }
        .tip-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .tip-img { width: 100%; height: 160px; object-fit: cover; }
        .tip-img-placeholder { width: 100%; height: 160px; display: flex; align-items: center; justify-content: center; font-size: 48px; }
        .tip-body { padding: 16px; }
        .tip-category { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; display: inline-block; margin-bottom: 8px; }
        .tip-title { font-size: 15px; font-weight: 600; color: #1a3a4f; margin-bottom: 6px; }
        .tip-content { font-size: 13px; color: #666; line-height: 1.6; }
        .tip-meta { font-size: 11px; color: #aaa; margin-top: 8px; }

        .add-tip-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 28px; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .btn-submit { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; border-radius: 10px; padding: 12px 28px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(41,128,185,0.3); }
        .upload-area { border: 1.5px dashed #b5d4f4; border-radius: 10px; padding: 16px; text-align: center; cursor: pointer; background: #f8fbff; transition: all .2s; }
        .upload-area:hover { border-color: #2980b9; background: #e6f1fb; }
        .alert-success { background: #eaf3de; border: none; border-radius: 10px; color: #27500a; padding: 12px 16px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="/">💊 PharmLocate</a>
        <div class="d-flex align-items-center">
            <a href="/" class="nav-pill">🏠 Home</a>
            <a href="/search" class="nav-pill">🔍 Search</a>
            @auth
                <a href="/dashboard" class="nav-pill">📊 Dashboard</a>
                <form method="POST" action="/logout" class="d-inline ms-1">
                    @csrf
                    <button type="submit" class="nav-pill nav-pill-danger" style="border:none">🚪 Log out</button>
                </form>
            @else
                <a href="/login" class="nav-pill">Log in</a>
                <a href="/register" class="nav-pill" style="background:#2980b9;border-color:#2980b9;color:white!important">Register</a>
            @endauth
        </div>
    </div>
</nav>

<div class="page-header text-white text-center">
    <div class="container">
        <h3 class="fw-bold mb-2">💡 Health tips</h3>
        <p class="opacity-75 mb-0">Wellness advice shared by the PharmLocate community</p>
    </div>
</div>

<div class="container py-4">

    @if(session('success'))
        <div class="alert-success mb-4">✅ {{ session('success') }}</div>
    @endif

    @auth
    <div class="add-tip-card">
        <h6 class="fw-bold mb-3">➕ Share a health tip</h6>
        <form method="POST" action="/health-tips" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label text-muted" style="font-size:13px">Tip title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        placeholder="e.g. Benefits of drinking warm water" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted" style="font-size:13px">Category</label>
                    <select name="category" class="form-select">
                        <option>Cardio</option>
                        <option>Nutrition</option>
                        <option>Fitness</option>
                        <option>Mental health</option>
                        <option>General</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label text-muted" style="font-size:13px">Description</label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                        rows="3" placeholder="Share your health tip details here..." required>{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted" style="font-size:13px">Image (optional)</label>
                    <div class="upload-area" onclick="document.getElementById('tip-image').click()">
                        <input type="file" id="tip-image" name="image" accept="image/*" style="display:none" onchange="showImageName(this)">
                        <div id="upload-placeholder">
                            <div style="font-size:28px;margin-bottom:4px">📷</div>
                            <div style="font-size:13px;color:#2980b9;font-weight:600">Click to upload image</div>
                            <small class="text-muted">JPG, PNG — Max 2MB</small>
                        </div>
                        <div id="upload-done" class="d-none">
                            <div style="font-size:28px;margin-bottom:4px">✅</div>
                            <div style="font-size:13px;color:#27500a;font-weight:600" id="image-name"></div>
                        </div>
                    </div>
                    @error('image')<div class="text-danger" style="font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-submit">✅ Submit tip</button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="text-center py-3 mb-4" style="background:white;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,0.05)">
        <p class="text-muted mb-2">Want to share a health tip?</p>
        <a href="/login" class="btn btn-primary btn-sm" style="background:#1a5276;border:none;border-radius:8px">Log in to share</a>
    </div>
    @endauth

    @if($tips->count() > 0)
    <div class="row g-4">
       @foreach($tips as $tip)
<div class="col-md-4">
    <div class="tip-card">
        @if($tip->image)
            <img src="{{ asset('storage/'.$tip->image) }}" alt="{{ $tip->title }}" class="tip-img">
        @else
                    <div class="tip-img-placeholder" style="background:
                        @if($tip->category=='Cardio') #e6f1fb
                        @elseif($tip->category=='Nutrition') #eaf3de
                        @elseif($tip->category=='Fitness') #faeeda
                        @elseif($tip->category=='Mental health') #f3e6fb
                        @else #f4f7fb @endif">
                        @if($tip->category=='Cardio') ❤️
                        @elseif($tip->category=='Nutrition') 🥦
                        @elseif($tip->category=='Fitness') 🏃
                        @elseif($tip->category=='Mental health') 🧠
                        @else 💡 @endif
                    </div>
                @endif
                <div class="tip-body">
                    <span class="tip-category" style="background:
                        @if($tip->category=='Cardio') #e6f1fb; color:#0c447c
                        @elseif($tip->category=='Nutrition') #eaf3de; color:#27500a
                        @elseif($tip->category=='Fitness') #faeeda; color:#633806
                        @elseif($tip->category=='Mental health') #f3e6fb; color:#6c3483
                        @else #f4f7fb; color:#555 @endif">
                        {{ $tip->category }}
                    </span>
                    <div class="tip-title">{{ $tip->title }}</div>
                    <div class="tip-content">{{ Str::limit($tip->content, 100) }}</div>
                    <div class="tip-meta">Shared by {{ $tip->user->name }} · {{ $tip->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <div style="font-size:48px;margin-bottom:12px">💡</div>
        <h6 class="text-muted">No health tips yet — be the first to share!</h6>
    </div>
    @endif

</div>

<footer style="background:#0f1e32;color:rgba(255,255,255,0.6);padding:24px 0;margin-top:50px">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold text-white">💊 PharmLocate</span>
        <small>© 2026 PharmLocate. All rights reserved.</small>
    </div>
</footer>

<script>
function showImageName(input) {
    if (input.files && input.files[0]) {
        document.getElementById('upload-placeholder').classList.add('d-none');
        document.getElementById('upload-done').classList.remove('d-none');
        document.getElementById('image-name').textContent = input.files[0].name;
    }
}
</script>
</body>
</html>