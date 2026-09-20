<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\HealthTip;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // 📊 Dashboard
    public function index()
    {
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalMedicines = Medicine::count();
        $pendingPharmacies = Pharmacy::where('status', 'pending')->with('user')->get();
        $recentOrders = Order::with(['customer', 'pharmacy'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalOrders', 'totalMedicines', 'pendingPharmacies', 'recentOrders'));
    }

    // 🏥 Pharmacies
    public function pharmacies()
    {
        $pharmacies = Pharmacy::with('user')->latest()->paginate(10);
        return view('admin.pharmacies', compact('pharmacies'));
    }
    public function approvePharmacy($id)
    {
        Pharmacy::findOrFail($id)->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Pharmacy approved!');
    }
    public function rejectPharmacy($id)
    {
        Pharmacy::findOrFail($id)->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Pharmacy rejected.');
    }

    // 👤 Users
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted.');
    }

    // 🛒 Orders
    public function orders()
    {
        $orders = Order::with(['customer', 'pharmacy'])->latest()->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    // 💡 Health Tips
    public function healthTips()
    {
        $tips = HealthTip::with('user')->latest()->paginate(10);
        return view('admin.health-tips', compact('tips'));
    }
    public function deleteHealthTip($id)
    {
        HealthTip::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Health tip deleted.');
    }
}