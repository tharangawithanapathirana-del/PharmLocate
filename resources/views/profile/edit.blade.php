<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 🟢 ඔබේ Dashboard එකේම CSS ටික */
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
        .section-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .profile-avatar { width: 80px; height: 80px; background: #e6f1fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 12px; }
        
        /* 🟢 මෙතන Text Box සහ Buttons වලට හරියට වැදෙන CSS */
        .form-label { font-weight: 600; color: #444; font-size: 14px; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 12px 16px; font-size: 14px; width: 100%; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .btn-save { background: #1a5276; border: none; border-radius: 8px; padding: 10px 24px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-save:hover { background: #154360; }
        .btn-danger { background: #e74c3c; border: none; border-radius: 8px; padding: 10px 24px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-danger:hover { background: #c0392b; }
        .alert-success { background: #eaf3de; border: none; border-radius: 10px; color: #27500a; padding: 12px 16px; margin-bottom: 16px; }
                /* 🟢 Password Show/Hide Button සඳහා CSS */
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #888;
            font-size: 18px;
            z-index: 5;
            padding: 0;
            line-height: 1;
        }
        .password-toggle:hover {
            color: #1a5276;
        }
    </style>
</head>
<body>

@include('layouts.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">My Profile</div>
            <div class="topbar-sub">View and update your personal information</div>
        </div>
    </div>

    <div class="content">
        @if(session('status') == 'profile-updated')
            <div class="alert-success">✅ Profile updated successfully!</div>
        @endif

        <div class="row g-4">
            {{-- Left Side: Profile Info Card --}}
            <div class="col-md-4">
                <div class="section-card text-center">
                    <div class="profile-avatar">👤</div>
                    <h5 class="fw-bold">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small">{{ auth()->user()->email }}</p>
                    <span style="background:#eaf3de;color:#27500a;font-size:12px;padding:4px 14px;border-radius:20px;font-weight:600">🏥 Pharmacy Owner</span>
                    <div class="mt-3 text-muted" style="font-size:12px">
                        Member since {{ auth()->user()->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>

            {{-- Right Side: Forms (අපි කෙලින්ම HTML දාමු) --}}
            <div class="col-md-8">
                
                {{-- 1. Update Profile Information --}}
                <div class="section-card">
                    <h6 class="fw-bold mb-4">✏️ Edit profile</h6>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required autofocus>
                            @error('name') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn-save">💾 Save</button>
                    </form>
                </div>

                {{-- 2. Update Password --}}
                <div class="section-card">
                    <h6 class="fw-bold mb-4">🔑 Change password</h6>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">👁️</button>
                                @error('current_password', 'updatePassword') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="new_password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">👁️</button>
                                @error('password', 'updatePassword') <div class="invalid-feedback">⚠️ {{ $message }}</div> @enderror
                            </div>
                        </div>

                       <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="confirm_password" class="form-control" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">👁️</button>
                            </div>
                        </div>

                        <button type="submit" class="btn-save">💾 Save</button>
                    </form>
                </div>

                {{-- 3. Delete Account --}}
                <div class="section-card">
                    <h6 class="fw-bold mb-4 text-danger">⚠️ Delete account</h6>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <p class="text-muted small mb-3">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">🗑️ Delete Account</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    // 🟢 Password එක Show/Hide කරන JavaScript function එක
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling; // ඊළඟට තියෙන button එක හොයාගන්න

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈'; // Password පෙන්වනකොට අයිකනය වෙනස් වෙයි
        } else {
            input.type = 'password';
            button.textContent = '👁️'; // Password හංගනකොට ආයෙත් අයිකනය වෙනස් වෙයි
        }
    }
</script>
</body>
</html>