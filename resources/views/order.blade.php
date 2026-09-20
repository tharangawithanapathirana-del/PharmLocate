<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7fb; }

        .navbar { background: rgba(15,30,50,0.97); backdrop-filter: blur(10px); padding: 14px 0; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: white !important; }
        .btn-back { background: rgba(255,255,255,0.1); color: white !important; border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 6px 16px; font-size: 13px; transition: all .2s; text-decoration: none; }
        .btn-back:hover { background: rgba(255,255,255,0.2); color: white !important; }

        .page-header { background: linear-gradient(135deg, #0f1e32, #1a5276); padding: 36px 0; }

        .order-card { background: white; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); border: none; overflow: hidden; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 12px 16px; font-size: 14px; transition: all .2s; }
        .form-control:focus, .form-select:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .form-label { font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }

        .fulfillment-btn { border: 1.5px solid #e0e8f0; border-radius: 14px; padding: 16px 20px; cursor: pointer; text-align: center; transition: all .2s; background: white; }
        .fulfillment-btn:hover { border-color: #2980b9; background: #f0f7fd; }
        .fulfillment-btn.active { border-color: #2980b9; background: #e6f1fb; }
        .fulfillment-btn .icon { font-size: 28px; display: block; margin-bottom: 6px; }
        .fulfillment-btn .label { font-size: 14px; font-weight: 600; color: #1a5276; }
        .fulfillment-btn .sub { font-size: 11px; color: #888; }

        .rx-box { background: linear-gradient(135deg, #eaf3de, #f0f7e8); border: 2px dashed #3b6d11; border-radius: 16px; padding: 24px; }
        .rx-upload-area { border: 1.5px dashed #3b6d11; border-radius: 12px; padding: 24px; text-align: center; background: white; cursor: pointer; transition: all .2s; }
        .rx-upload-area:hover { background: #f0f7e8; }
        .rx-upload-area input { display: none; }

        .summary-card { background: linear-gradient(135deg, #0f1e32, #1a5276); border-radius: 16px; padding: 20px; color: white; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 14px; }
        .summary-row:last-child { border-bottom: none; }
        .summary-total { font-size: 22px; font-weight: 800; color: #5dade2; }

        .btn-confirm { background: linear-gradient(135deg, #e67e22, #d35400); border: none; border-radius: 14px; padding: 18px; font-size: 16px; font-weight: 700; color: white; width: 100%; transition: all .2s; }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(230,126,34,0.4); color: white; }

        .step-indicator { display: flex; align-items: center; gap: 8px; margin-bottom: 28px; }
        .step { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; }
        .step.done { background: #eaf3de; color: #27500a; }
        .step.active { background: #1a5276; color: white; }
        .step.pending { background: #f0f0f0; color: #aaa; }
        .step-line { flex: 1; height: 2px; background: #e0e8f0; }
        .step-line.done { background: #3b6d11; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="/">💊 PharmLocate</a>
       <a href="/search?q={{ urlencode(request()->query('q', '')) }}" class="btn-back">← Back to results</a>
    </div>
</nav>

<!-- Page header -->
<div class="page-header text-white text-center">
    <div class="container">
        <h4 class="fw-bold mb-1">Place order</h4>
        <p class="opacity-75 small mb-0">{{ $medicine->pharmacy->name ?? 'N/A' }} — {{ $medicine->pharmacy->address ?? '' }}</p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center g-4">
        <div class="col-lg-7">
            {{-- 🟢 Form එකට ID එකක් දීලා, action සහ method හරියට දාලා තියෙනවා --}}
            <form id="orderForm" method="POST" action="/order" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
                <input type="hidden" name="delivery_type" id="deliveryType" value="delivery">

                <div class="order-card p-4">

                    <!-- Step indicator -->
                    <div class="step-indicator">
                        <div class="step done">✓</div>
                        <div class="step-line done"></div>
                        <div class="step done">✓</div>
                        <div class="step-line done"></div>
                        <div class="step active">3</div>
                        <div class="step-line"></div>
                        <div class="step pending">4</div>
                    </div>
                    <div class="d-flex justify-content-between mb-4" style="font-size:11px;color:#888;margin-top:-20px">
                        <span>Search</span>
                        <span>Detail</span>
                        <span style="color:#1a5276;font-weight:600">Order</span>
                        <span>Confirm</span>
                    </div>

                    <!-- Medicine info -->
                    <div class="mb-4 p-3" style="background:#f4f7fb;border-radius:12px">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;background:#e6f1fb;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px">💊</div>
                            <div>
                                <div class="fw-bold" style="color:#1a5276">{{ $medicine->name }}</div>
                                <small class="text-muted">{{ $medicine->pharmacy->name ?? 'N/A' }} &nbsp;|&nbsp; LKR {{ number_format($medicine->price, 2) }} per tablet</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label class="form-label">Quantity</label>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" onclick="changeQty(-1)" style="width:40px;height:40px;border-radius:10px;border:1.5px solid #e0e8f0;background:white;font-size:18px;font-weight:700;color:#1a5276;cursor:pointer">−</button>
                            <input type="number" class="form-control text-center fw-bold" id="qty" value="1" min="1" max="{{ $medicine->quantity }}" oninput="updateTotal()" style="width:80px;font-size:18px">
                            <button type="button" onclick="changeQty(1)" style="width:40px;height:40px;border-radius:10px;border:1.5px solid #e0e8f0;background:white;font-size:18px;font-weight:700;color:#1a5276;cursor:pointer">+</button>
                            <small class="text-muted">Max: {{ $medicine->quantity }} tablets</small>
                        </div>
                    </div>

                    <!-- Fulfillment -->
                    <div class="mb-4">
                        <label class="form-label">Fulfillment type</label>
                        <div class="d-flex gap-3">
                            <div class="fulfillment-btn active flex-fill" onclick="selectType(this,'delivery')" id="btn-delivery">
                                <span class="icon">🚚</span>
                                <span class="label">Delivery</span>
                                <span class="sub">Delivered to your address</span>
                            </div>
                            <div class="fulfillment-btn flex-fill" onclick="selectType(this,'pickup')" id="btn-pickup">
                                <span class="icon">🏪</span>
                                <span class="label">Pickup</span>
                                <span class="sub">Collect from pharmacy</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery address -->
                    <div class="mb-4" id="address-section">
                        <label class="form-label">Delivery address</label>
                        <input type="text" name="address" class="form-control" value="{{ auth()->user()->address ?? '' }}" placeholder="No 12, Duplication Rd, Colombo 03">
                    </div>

                    <!-- Prescription upload -->
                    <div class="rx-box mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span style="font-size:20px">📋</span>
                            <span class="fw-bold" style="color:#27500a">Prescription upload</span>
                            <span style="background:#eaf3de;color:#27500a;font-size:11px;padding:2px 10px;border-radius:20px;font-weight:600">Required</span>
                        </div>
                        <p class="text-muted small mb-3">This medicine requires a valid doctor's prescription. Upload a clear photo or scan.</p>
                        <div class="rx-upload-area" onclick="document.getElementById('rx-file').click()">
                            <input type="file" name="prescription" id="rx-file" accept=".jpg,.jpeg,.png,.pdf" onchange="showPreview(this)">
                            <div id="rx-placeholder">
                                <div style="font-size:32px;margin-bottom:8px">📁</div>
                                <div class="fw-bold" style="color:#3b6d11;font-size:14px">Click to upload prescription</div>
                                <small class="text-muted">JPG, PNG, PDF — Max 5MB</small>
                            </div>
                            <div id="rx-preview" class="d-none">
                                <div style="font-size:32px;margin-bottom:8px">✅</div>
                                <div class="fw-bold" style="color:#27500a;font-size:14px" id="rx-filename"></div>
                                <small style="color:#3b6d11">Prescription uploaded successfully</small>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label">Notes <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions for the pharmacy..."></textarea>
                    </div>

                </div>
            </form>
        </div>

        <!-- Right: Summary -->
        <div class="col-lg-4">
            <div style="position:sticky;top:80px">
                <div class="summary-card mb-3">
                    <div class="fw-bold mb-3 opacity-75" style="font-size:13px;text-transform:uppercase;letter-spacing:.05em">Order summary</div>
                    <div class="summary-row">
                        <span class="opacity-75">Medicine</span>
                        <span>{{ $medicine->name }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="opacity-75">Pharmacy</span>
                        <span>{{ $medicine->pharmacy->name ?? 'N/A' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="opacity-75">Unit price</span>
                        <span>LKR {{ number_format($medicine->price, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="opacity-75">Quantity</span>
                        <span id="summary-qty">1</span>
                    </div>
                    <div class="summary-row" style="border-bottom:none;padding-top:12px;margin-top:4px;border-top:1px solid rgba(255,255,255,0.15)">
                        <span class="fw-bold">Total</span>
                        <span class="summary-total" id="summary-total">LKR {{ number_format($medicine->price, 2) }}</span>
                    </div>
                </div>

                <button type="button" class="btn-confirm" onclick="document.getElementById('orderForm').submit();">
                    🛒 Confirm order
                </button>

                <div class="mt-3 p-3" style="background:white;border-radius:12px;border:1.5px solid #e0e8f0">
                    <small class="text-muted" style="font-size:12px">
                        🔒 <strong>Secure order</strong> — Your prescription and personal data are stored securely and only visible to the pharmacy.
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

<footer style="background:#0f1e32;color:rgba(255,255,255,0.6);padding:24px 0;margin-top:40px">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold text-white">💊 PharmLocate</span>
        <small>© 2026 PharmLocate. All rights reserved.</small>
    </div>
</footer>

<script>
function changeQty(n) {
    const input = document.getElementById('qty');
    const maxQty = parseInt(input.getAttribute('max'));
    const val = Math.min(maxQty, Math.max(1, parseInt(input.value) + n));
    input.value = val;
    document.getElementById('hiddenQuantity').value = val;
    updateTotal();
}

function updateTotal() {
    const qty = parseInt(document.getElementById('qty').value) || 0;
    const unitPrice = {{ $medicine->price }};
    document.getElementById('summary-qty').textContent = qty;
    document.getElementById('summary-total').textContent = 'LKR ' + (qty * unitPrice).toFixed(2);
}

function selectType(el, type) {
    document.getElementById('btn-delivery').classList.remove('active');
    document.getElementById('btn-pickup').classList.remove('active');
    el.classList.add('active');
    document.getElementById('deliveryType').value = type;
    document.getElementById('address-section').style.display = type === 'delivery' ? 'block' : 'none';
}

function showPreview(input) {
    if (input.files && input.files[0]) {
        document.getElementById('rx-placeholder').classList.add('d-none');
        document.getElementById('rx-preview').classList.remove('d-none');
        document.getElementById('rx-filename').textContent = input.files[0].name;
    }
}
</script>
</body>
</html>