@extends('layouts.admin')

@section('content')
<style>
    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        border: 1px solid #e8f0fe;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: 0 auto;
    }
    .report-icon { font-size: 48px; margin-bottom: 12px; }
    .form-control {
        border-radius: 12px;
        border: 1.5px solid #e0e8f0;
        padding: 12px 16px;
        font-size: 14px;
        background: #fafcff;
    }
    .form-control:focus {
        border-color: #2980b9;
        box-shadow: 0 0 0 4px rgba(41,128,185,0.1);
        background: white;
    }
    .btn-download {
        background: linear-gradient(135deg, #1a5276, #2980b9);
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        color: white;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all .3s;
        box-shadow: 0 4px 14px rgba(26,82,118,0.3);
    }
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(26,82,118,0.4);
    }
    .info-box {
        background: #f8fafd;
        border-radius: 12px;
        padding: 16px 20px;
        border-left: 4px solid #2980b9;
        margin-top: 20px;
    }
    .info-box ul { padding-left: 18px; margin-bottom: 0; }
    .info-box ul li { font-size: 13px; color: #555; line-height: 1.8; }
</style>

<div class="admin-card">
    <div class="text-center mb-4">
        <div class="report-icon">📊</div>
        <h4 class="fw-bold" style="color:#183153;">Generate Admin Report</h4>
        <p class="text-muted small">Download a comprehensive PDF report of the entire system</p>
    </div>

    <form method="POST" action="{{ route('admin.reports.download') }}" class="row g-3">
        @csrf
        <div class="col-12 text-center">
            <button type="submit" class="btn-download w-100">
                ⬇️ Download System Report (PDF)
            </button>
        </div>
    </form>

    <div class="info-box">
        <p class="fw-bold mb-1" style="font-size:14px; color:#183153;">📋 Report Contents</p>
        <ul>
            <li>Total Users, Pharmacies, Medicines & Orders</li>
            <li>Total System Revenue</li>
            <li>Pending Pharmacy Approvals</li>
            <li>Recent Orders List</li>
        </ul>
    </div>
</div>
@endsection