<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pharmacy;
use App\Models\Order;
use App\Models\Medicine;

class PharmacyDashboardController extends Controller
{
    public function index()
    {
        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();

        if ($pharmacy) {
            // Statistics සඳහා
            $totalOrders = Order::where('pharmacy_id', $pharmacy->id)->count();
            $pendingOrders = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'pending')->count();
            $totalMedicines = Medicine::where('pharmacy_id', $pharmacy->id)->count();
            $lowStockItems = Medicine::where('pharmacy_id', $pharmacy->id)
                                      ->where('quantity', '>', 0)
                                      ->where('quantity', '<=', 10)
                                      ->get();

            // 🟢 Recent Orders (අලුත්ම 5ක්)
            $recentOrders = Order::with(['customer', 'items.medicine'])
                                 ->where('pharmacy_id', $pharmacy->id)
                                 ->latest()
                                 ->take(5)
                                 ->get();

            // 🟢 Pending Prescriptions (Rx Image තියෙන Pending Orders 5ක්)
            $pendingPrescriptions = Order::with(['customer', 'items.medicine'])
                                         ->where('pharmacy_id', $pharmacy->id)
                                         ->where('status', 'pending')
                                         ->whereNotNull('prescription_image')
                                         ->latest()
                                         ->take(2)
                                         ->get();
        } else {
            $totalOrders = 0; $pendingOrders = 0; $totalMedicines = 0;
            $lowStockItems = collect();
            $recentOrders = collect();
            $pendingPrescriptions = collect();
        }

        return view('pharmacy-dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalMedicines',
            'lowStockItems', 'recentOrders', 'pendingPrescriptions'
        ));
    }
}