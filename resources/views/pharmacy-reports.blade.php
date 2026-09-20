<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 🟢 Dashboard එකේ තියෙන Styles ඔක්කොම මෙතනට */
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
        .card { border-radius: 16px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .form-control { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; }
        .form-control:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .btn-primary { background: #1a5276; border: none; border-radius: 8px; padding: 12px 28px; color: white; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-primary:hover { background: #154360; }
    </style>
</head>
<body>

@include('layouts.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">📊 Reports</div>
            <div class="topbar-sub">Generate pharmacy performance and stock reports</div>
        </div>
    </div>

    <div class="content">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Download Pharmacy Report</h5>
            <form method="POST" action="{{ route('pharmacy.reports.download') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn-primary w-100">⬇️ Download Report (Excel)</button>
                </div>
            </form>
            <div class="mt-4 text-muted small">
                <p>This report will include:<br>
                - Total Orders & Total Revenue <br>
                - Stock Items & Low Stock Alerts <br>
                - Detailed Order List with Customer, Medicine, Qty & Status</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>