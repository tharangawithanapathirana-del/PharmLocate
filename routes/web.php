<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HealthTipController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PharmacyOrderController;
use App\Http\Controllers\PharmacyDashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\HealthTip;
use App\Http\Controllers\AdminReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes (ලොග් වීම අවශ්‍ය නැති පිටු)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $healthTips = HealthTip::latest()->take(3)->get();
    return view('welcome', compact('healthTips'));
});

Route::get('/search', [SearchController::class, 'index']);
Route::get('/medicine-detail', function () {
    $id = request()->query('id');
    if (!$id) {
        return redirect('/search');
    }
    $medicine = Medicine::with('pharmacy')->findOrFail($id);
    return view('medicine-detail', compact('medicine'));
});

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::get('/health-tips', [HealthTipController::class, 'index']);
Route::post('/health-tips', [HealthTipController::class, 'store'])->middleware('auth');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes (ලොග් වීම අවශ්‍ය පිටු)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update', [ProfileController::class, 'update']);
});

// 🟢 Role අනුව Dashboard එකට හරවන Route එක (Admin, Pharmacy, Customer)
// 🟢 ආරක්ෂාවට 'verified' middleware එක නැවත එකතු කරලා තියෙනවා
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        // 🟢 කෙලින්ම URL එක දාන්න (Route name එක වෙනුවට)
        return redirect('http://127.0.0.1:8000/admin/dashboard');
    } elseif ($user->role === 'pharmacy') {
        return redirect('/pharmacy-dashboard');
    }
    return redirect('/customer-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/customer-dashboard', function () {
    $healthTips = HealthTip::latest()->take(3)->get();
    $orders = Order::with(['pharmacy', 'items.medicine', 'customer'])
                    ->where('customer_id', Auth::id())
                    ->latest()
                    ->get();
    return view('customer-dashboard', compact('orders', 'healthTips'));
})->middleware(['auth']);

Route::get('/pharmacy-dashboard', [PharmacyDashboardController::class, 'index'])
    ->middleware(['auth'])->name('pharmacy.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/stock-management', [MedicineController::class, 'index']);
    Route::post('/stock-management', [MedicineController::class, 'store']);
    Route::post('/stock-management/{id}/update', [MedicineController::class, 'update']);
    Route::post('/stock-management/{id}/delete', [MedicineController::class, 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pharmacy-orders', [PharmacyOrderController::class, 'index'])->name('pharmacy.orders');
    Route::post('/order/{id}/status', [PharmacyOrderController::class, 'updateStatus'])->name('order.updateStatus');
});

Route::delete('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware(['auth']);
Route::get('/order-detail', function () {
    $id = request()->query('id');
    if (!$id) {
        return redirect('/customer-dashboard');
    }
    $order = Order::with(['pharmacy', 'items.medicine'])
                  ->where('customer_id', Auth::id())
                  ->where('id', $id)
                  ->firstOrFail();
    return view('order-detail', compact('order'));
})->middleware(['auth'])->name('customer.order-detail');

/*
|--------------------------------------------------------------------------
| 🟢 Admin Panel Routes (Admin Middleware අවශ්‍යයි)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/pharmacies', [AdminController::class, 'pharmacies'])->name('pharmacies');
    Route::post('/pharmacy/{id}/approve', [AdminController::class, 'approvePharmacy'])->name('pharmacy.approve');
    Route::post('/pharmacy/{id}/reject', [AdminController::class, 'rejectPharmacy'])->name('pharmacy.reject');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/health-tips', [AdminController::class, 'healthTips'])->name('health-tips');
    Route::delete('/health-tips/{id}/delete', [AdminController::class, 'deleteHealthTip'])->name('health-tips.delete');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::post('/reports/download', [AdminReportController::class, 'downloadReport'])->name('reports.download');
});

use App\Http\Controllers\ReportController;

Route::middleware(['auth'])->group(function () {
    Route::get('/pharmacy-reports', [ReportController::class, 'index'])->name('pharmacy.reports');
    Route::post('/pharmacy-reports/download', [ReportController::class, 'downloadReport'])->name('pharmacy.reports.download');
});


Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('pharmacy.prescriptions')->middleware(['auth']);
Route::post('/order/{id}/verify-prescription', [OrderController::class, 'verifyPrescription'])->name('order.verifyPrescription')->middleware(['auth']);
Route::post('/order/{id}/reject-prescription', [OrderController::class, 'rejectPrescription'])->name('order.rejectPrescription')->middleware(['auth']);