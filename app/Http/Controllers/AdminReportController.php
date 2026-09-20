<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function index()
    {
        return view('admin.reports');
    }

    public function downloadReport(Request $request)
    {
        // 1. Statistics Data
        $totalUsers = User::count();
        $totalPharmacies = Pharmacy::count();
        $totalMedicines = Medicine::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');

        // 2. Pending Pharmacies
        $pendingPharmacies = Pharmacy::where('status', 'pending')->with('user')->get();

        // 3. Recent Orders (අලුත්ම 20)
        $recentOrders = Order::with(['customer', 'pharmacy', 'items.medicine'])
                             ->latest()
                             ->take(20)
                             ->get();

        $data = [
            'totalUsers' => $totalUsers,
            'totalPharmacies' => $totalPharmacies,
            'totalMedicines' => $totalMedicines,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'pendingPharmacies' => $pendingPharmacies,
            'recentOrders' => $recentOrders,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);
        return $pdf->download('pharmlocate_admin_report_' . date('Y-m-d') . '.pdf');
    }
}