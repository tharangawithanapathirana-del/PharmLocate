<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management — PharmLocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .stat-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 16px; transition: all .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .stat-lbl { font-size: 13px; color: #888; margin-top: 3px; }
        .section-card { background: white; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .stock-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
        .stock-table th { background: #f4f7fb; color: #555; font-weight: 600; padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .stock-table th:first-child { border-radius: 10px 0 0 10px; }
        .stock-table th:last-child { border-radius: 0 10px 10px 0; }
        .stock-table td { padding: 12px 14px; border-bottom: 1px solid #f0f4f8; color: #333; vertical-align: middle; }
        .stock-table tr:last-child td { border-bottom: none; }
        .stock-table tr:hover td { background: #fafcff; }
        .badge-instock { background: #eaf3de; color: #27500a; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-low { background: #faeeda; color: #633806; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-out { background: #fcebeb; color: #791f1f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-rx { background: #eeedfe; color: #3c3489; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-norx { background: #eaf3de; color: #27500a; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .btn-edit { background: #e6f1fb; color: #0c447c; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-edit:hover { background: #2980b9; color: white; }
        .btn-delete { background: #fcebeb; color: #791f1f; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-delete:hover { background: #a32d2d; color: white; }
        .btn-add { background: linear-gradient(135deg, #1a5276, #2980b9); border: none; border-radius: 10px; padding: 10px 20px; color: white; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; text-decoration: none; display: inline-block; }
        .btn-add:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(41,128,185,0.3); color: white; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e8f0; padding: 10px 14px; font-size: 14px; transition: all .2s; }
        .form-control:focus, .form-select:focus { border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,0.12); }
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
        .modal-header { background: linear-gradient(135deg, #0f1e32, #1a5276); border-radius: 16px 16px 0 0; padding: 20px 24px; }
        .modal-title { color: white; font-weight: 600; }
        .modal-header .btn-close { filter: invert(1); }
        .search-bar { background: white; border-radius: 12px; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .alert-success { background: #eaf3de; border: none; border-radius: 10px; color: #27500a; padding: 12px 16px; margin-bottom: 16px; }
        .alert-error { background: #fcebeb; border: none; border-radius: 10px; color: #791f1f; padding: 12px 16px; margin-bottom: 16px; }
    </style>
</head>
<body>

@include('layouts.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Stock management</div>
            <div class="topbar-sub">Manage your medicine inventory</div>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addMedicineModal">➕ Add medicine</button>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#e6f1fb">💊</div>
                    <div><div class="stat-num" style="color:#1a5276">{{ $medicines->count() }}</div><div class="stat-lbl">Total medicines</div></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eaf3de">✅</div>
                    <div><div class="stat-num" style="color:#27500a">{{ $medicines->where('quantity', '>', 10)->count() }}</div><div class="stat-lbl">In stock</div></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#faeeda">⚠️</div>
                    <div><div class="stat-num" style="color:#854f0b">{{ $medicines->whereBetween('quantity', [1, 10])->count() }}</div><div class="stat-lbl">Low stock</div></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fcebeb">❌</div>
                    <div><div class="stat-num" style="color:#a32d2d">{{ $medicines->where('quantity', 0)->count() }}</div><div class="stat-lbl">Out of stock</div></div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-bar d-flex gap-3 align-items-center flex-wrap">
            <div style="flex:1;min-width:200px">
                <input type="text" class="form-control" placeholder="🔍 Search medicine by name..." id="searchInput" oninput="filterTable()">
            </div>
            <select class="form-select" style="width:160px" id="categoryFilter" onchange="filterTable()">
                <option value="">All categories</option>
                <option>Analgesic</option>
                <option>Antibiotic</option>
                <option>Antidiabetic</option>
                <option>Antihypertensive</option>
                <option>Vitamin</option>
                <option>Other</option>
            </select>
        </div>

        <!-- Table -->
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Medicine inventory</h6>
                <small class="text-muted">{{ $medicines->count() }} medicines</small>
            </div>
            <div style="overflow-x:auto">
                <table class="stock-table" id="stockTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine name</th>
                            <th>Category</th>
                            {{-- 🟢 නව Columns 3ක් එකතු කරලා --}}
                            <th>Dosage</th>
                            <th>Strength</th>
                            <th>Manufacturer</th>
                            <th>Price (LKR)</th>
                            <th>Quantity</th>
                            <th>Expiry date</th>
                            <th>Rx required</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($medicines as $index => $medicine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $medicine->name }}</strong><br>
                                <small class="text-muted">{{ $medicine->generic_name }}</small>
                            </td>
                            <td>{{ $medicine->category }}</td>
                            {{-- 🟢 නව දත්ත පෙන්වන Columns --}}
                            <td>{{ $medicine->dosage_form ?? '—' }}</td>
                            <td>{{ $medicine->strength ?? '—' }}</td>
                            <td>{{ $medicine->manufacturer ?? '—' }}</td>
                            <td>{{ number_format($medicine->price, 2) }}</td>
                            <td style="{{ $medicine->quantity == 0 ? 'color:#a32d2d;font-weight:600' : ($medicine->quantity <= 10 ? 'color:#854f0b;font-weight:600' : '') }}">
                                {{ $medicine->quantity }}
                            </td>
                            <td>{{ $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date)->format('M Y') : '—' }}</td>
                            <td>
                                @if($medicine->is_rx_required)
                                    <span class="badge-rx">Rx required</span>
                                @else
                                    <span class="badge-norx">No Rx</span>
                                @endif
                            </td>
                            <td>
                                @if($medicine->quantity == 0)
                                    <span class="badge-out">Out of stock</span>
                                @elseif($medicine->quantity <= 10)
                                    <span class="badge-low">Low stock</span>
                                @else
                                    <span class="badge-instock">In stock</span>
                                @endif
                            </td>
                            <td>
                                {{-- 🟢 Edit Modal එකට අලුත් Parameters 3ක් යවන විදියට update කරලා --}}
                                <button class="btn-edit me-1"
                                    onclick="openEditModal(
                                        {{ $medicine->id }},
                                        '{{ $medicine->name }}',
                                        '{{ $medicine->generic_name }}',
                                        '{{ $medicine->category }}',
                                        {{ $medicine->price }},
                                        {{ $medicine->quantity }},
                                        '{{ $medicine->expiry_date }}',
                                        {{ $medicine->is_rx_required ? 1 : 0 }},
                                        '{{ $medicine->description }}',
                                        '{{ $medicine->dosage_form }}',
                                        '{{ $medicine->strength }}',
                                        '{{ $medicine->manufacturer }}'
                                    )">✏️ Edit</button>
                                <form method="POST" action="/stock-management/{{ $medicine->id }}/delete" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                    @csrf
                                    <button type="submit" class="btn-delete">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-muted">
                                No medicines in stock yet. Click "Add medicine" to get started.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Add new medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/stock-management" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Medicine name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Generic name</label>
                            <input type="text" name="generic_name" class="form-control" placeholder="e.g. Acetaminophen">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select category</option>
                                <option>Analgesic</option>
                                <option>Antibiotic</option>
                                <option>Antidiabetic</option>
                                <option>Antihypertensive</option>
                                <option>Vitamin</option>
                                <option>Other</option>
                            </select>
                        </div>
                        {{-- 🟢 Add Form එකට අලුත් Input 3ක් එකතු කරලා --}}
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Dosage form</label>
                            <input type="text" name="dosage_form" class="form-control" placeholder="e.g. Tablet, Syrup">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Strength</label>
                            <input type="text" name="strength" class="form-control" placeholder="e.g. 500mg, 10ml">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Price (LKR) *</label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Quantity *</label>
                            <input type="number" name="quantity" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Manufacturer</label>
                            <input type="text" name="manufacturer" class="form-control" placeholder="e.g. CIC Holdings">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Expiry date</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Prescription required</label>
                            <select name="is_rx_required" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Medicine image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:0.5px solid #e0e8f0;padding:16px 24px">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px">Cancel</button>
                    <button type="submit" class="btn-add">💾 Save medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal fade" id="editMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Edit medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Medicine name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Generic name</label>
                            <input type="text" name="generic_name" id="edit_generic_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Category *</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <option>Analgesic</option>
                                <option>Antibiotic</option>
                                <option>Antidiabetic</option>
                                <option>Antihypertensive</option>
                                <option>Vitamin</option>
                                <option>Other</option>
                            </select>
                        </div>
                        {{-- 🟢 Edit Form එකට අලුත් Input 3ක් එකතු කරලා --}}
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Dosage form</label>
                            <input type="text" name="dosage_form" id="edit_dosage_form" class="form-control" placeholder="e.g. Tablet, Syrup">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Strength</label>
                            <input type="text" name="strength" id="edit_strength" class="form-control" placeholder="e.g. 500mg, 10ml">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Price (LKR) *</label>
                            <input type="number" name="price" id="edit_price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Quantity *</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Manufacturer</label>
                            <input type="text" name="manufacturer" id="edit_manufacturer" class="form-control" placeholder="e.g. CIC Holdings">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Expiry date</label>
                            <input type="date" name="expiry_date" id="edit_expiry_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Prescription required</label>
                            <select name="is_rx_required" id="edit_rx" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Medicine image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#555">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:0.5px solid #e0e8f0;padding:16px 24px">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px">Cancel</button>
                    <button type="submit" class="btn-add">💾 Update medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 🟢 Function එකට අලුත් Parameters 3ක් එකතු කරලා
function openEditModal(id, name, generic_name, category, price, quantity, expiry_date, rx, description, dosage_form, strength, manufacturer) {
    document.getElementById('editForm').action = '/stock-management/' + id + '/update';
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_generic_name').value = generic_name;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_expiry_date').value = expiry_date;
    document.getElementById('edit_rx').value = rx;
    document.getElementById('edit_description').value = description;
    
    // 🟢 අලුත් අගයන් Input වලට fill කරන කොටස
    document.getElementById('edit_dosage_form').value = dosage_form;
    document.getElementById('edit_strength').value = strength;
    document.getElementById('edit_manufacturer').value = manufacturer;
    
    new bootstrap.Modal(document.getElementById('editMedicineModal')).show();
}

function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
        const rowCategory = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() ?? '';
        const matchSearch = name.includes(search);
        const matchCategory = category === '' || rowCategory.includes(category);
        row.style.display = matchSearch && matchCategory ? '' : 'none';
    });
}
</script>
</body>
</html>