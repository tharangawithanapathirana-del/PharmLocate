<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a5276, #2980b9); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px 0; }
        .auth-card { background: white; border-radius: 20px; padding: 40px; width: 100%; max-width: 460px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .brand-logo { width: 60px; height: 60px; background: #e6f1fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 12px; }
        .form-control { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 12px 16px; font-size: 14px; transition: all .2s; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .form-control.is-invalid { border-color: #e74c3c; background-image: none; }
        .invalid-feedback { font-size: 12px; color: #e74c3c; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .btn-register { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; padding: 12px; font-size: 16px; font-weight: 600; border-radius: 10px; color: white; width: 100%; cursor: pointer; transition: all .2s; }
        .btn-register:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(41,128,185,0.3); }
        .role-btn { border: 1.5px solid #e0e8f0; border-radius: 10px; padding: 10px; cursor: pointer; text-align: center; transition: all .2s; }
        .role-btn.active { border-color: #2980b9; background: #e6f1fb; color: #0c447c; font-weight: 600; }
        .alert-error { background: #fcebeb; border: 1px solid #f09595; border-radius: 10px; padding: 12px 16px; color: #791f1f; font-size: 13px; margin-bottom: 16px; }
        .password-rules { background: #f4f7fb; border-radius: 8px; padding: 10px 14px; font-size: 12px; color: #666; margin-top: 6px; }
        .password-rules li { margin-bottom: 2px; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="brand-logo">💊</div>
        <h4 class="fw-bold mb-0">PharmLocate</h4>
        <small class="text-muted">Sri Lanka Private Pharmacy Association</small>
    </div>

    <h5 class="fw-bold mb-1">Create an account</h5>
    <p class="text-muted small mb-3">Join PharmLocate today</p>

    @if ($errors->any())
        <div class="alert-error">
            ❌ Please fix the errors below and try again.
        </div>
    @endif

    <div class="mb-4">
        <label class="form-label text-muted">I am a</label>
        <div class="d-flex gap-3">
            <div class="role-btn active flex-fill" onclick="selectRole(this,'customer')" id="btn-customer">👤 Customer</div>
            <div class="role-btn flex-fill" onclick="selectRole(this,'pharmacy')" id="btn-pharmacy">🏥 Pharmacy</div>
        </div>
    </div>

    <form method="POST" action="/register">
        @csrf
        <input type="hidden" name="role" id="role-input" value="customer">
        <div class="mb-3" id="location-section" style="display:none">
    <label class="form-label">Pharmacy location</label>
    <div class="row g-2">
        <div class="col-6">
            <input type="text" name="latitude" id="latitude" class="form-control"
                placeholder="Latitude e.g. 6.9271">
        </div>
        <div class="col-6">
            <input type="text" name="longitude" id="longitude" class="form-control"
                placeholder="Longitude e.g. 79.8612">
        </div>
         <small class="text-muted">Find coordinates: <a href="https://maps.google.com" target="_blank">Google Maps</a></small>
    </div>
</div>
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="form-control form-control-lg @error('name') is-invalid @enderror"
                placeholder="Kasun Perera" required>
            @error('name')
                <div class="invalid-feedback">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="you@example.com" required>
            @error('email')
                <div class="invalid-feedback">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Phone number</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                class="form-control form-control-lg @error('phone') is-invalid @enderror"
                placeholder="071-234-5678">
            @error('phone')
                <div class="invalid-feedback">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
    <label class="form-label">Address</label>
    <div class="position-relative">
        <textarea name="address"
            class="form-control @error('address') is-invalid @enderror"
            placeholder="No 45, Galle Rd, Colombo 03"
            rows="2">{{ old('address') }}</textarea>
    </div>
    @error('address')
        <div class="invalid-feedback" style="display:flex">⚠️ {{ $message }}</div>
    @enderror
</div>

        <div class="mb-3">
    <label class="form-label">Password</label>
    <div class="position-relative">
        <input type="password" name="password" id="password"
            class="form-control form-control-lg @error('password') is-invalid @enderror"
            placeholder="••••••••" required>
        <button type="button" onclick="togglePassword('password')"
            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:18px">
            👁️
        </button>
    </div>
    @error('password')
        <div class="invalid-feedback" style="display:flex">⚠️ {{ $message }}</div>
    @enderror
    <ul class="password-rules mt-2">
        <li>✅ Minimum 8 characters</li>
        <li>✅ At least one uppercase letter</li>
        <li>✅ At least one number</li>
    </ul>
</div>

<div class="mb-4">
    <label class="form-label">Confirm password</label>
    <div class="position-relative">
        <input type="password" name="password_confirmation" id="password_confirmation"
            class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
            placeholder="••••••••" required>
        <button type="button" onclick="togglePassword('password_confirmation')"
            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:18px">
            👁️
        </button>
    </div>
    @error('password_confirmation')
        <div class="invalid-feedback" style="display:flex">⚠️ {{ $message }}</div>
    @enderror
</div>

        <button type="submit" class="btn-register">Create account</button>
    </form>

    <hr class="my-4">
    <p class="text-center text-muted small mb-0">
        Already have an account? <a href="/login" class="text-primary fw-bold">Log in</a>
    </p>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}
function selectRole(el, role) {
    document.getElementById('btn-customer').classList.remove('active');
    document.getElementById('btn-pharmacy').classList.remove('active');
    el.classList.add('active');
    document.getElementById('role-input').value = role;
    document.getElementById('location-section').style.display = role === 'pharmacy' ? 'block' : 'none';
}
</script>
</body>
</html>