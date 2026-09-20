<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a5276, #2980b9); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: white; border-radius: 20px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .brand-logo { width: 60px; height: 60px; background: #e6f1fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 12px; }
        .form-control { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 12px 16px; font-size: 14px; transition: all .2s; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .form-control.is-invalid { border-color: #e74c3c; background-image: none; }
        .invalid-feedback { font-size: 12px; color: #e74c3c; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .btn-login { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; padding: 12px; font-size: 16px; font-weight: 600; border-radius: 10px; color: white; width: 100%; cursor: pointer; transition: all .2s; }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(41,128,185,0.3); }
        .alert-error { background: #fcebeb; border: 1px solid #f09595; border-radius: 10px; padding: 12px 16px; color: #791f1f; font-size: 13px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="brand-logo">💊</div>
        <h4 class="fw-bold mb-0">PharmLocate</h4>
        <small class="text-muted">Sri Lanka Private Pharmacy Association</small>
    </div>

    <h5 class="fw-bold mb-1">Welcome back</h5>
    <p class="text-muted small mb-4">Log in to your account to continue</p>

    @if ($errors->any())
        <div class="alert-error">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-500">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="you@example.com" required autofocus>
            @error('email')
                <div class="invalid-feedback">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
    <div class="d-flex justify-content-between">
        <label class="form-label fw-500">Password</label>
        <a href="/forgot-password" class="small text-primary">Forgot password?</a>
    </div>
    <div class="position-relative">
        <input type="password" name="password" id="password"
            class="form-control form-control-lg @error('password') is-invalid @enderror"
            placeholder="••••••••" required>
        <button type="button" onclick="togglePassword('password','eye1')"
            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:18px">
            👁️
        </button>
    </div>
    @error('password')
        <div class="invalid-feedback" style="display:flex">⚠️ {{ $message }}</div>
    @enderror
</div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label text-muted small" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn-login">Log in</button>
    </form>

    <hr class="my-4">
    <p class="text-center text-muted small mb-0">
        Don't have an account? <a href="/register" class="text-primary fw-bold">Register</a>
    </p>
</div>

<script>
function togglePassword(fieldId, eyeId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>