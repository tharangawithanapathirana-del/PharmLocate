<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyOrderController extends Controller
{
    public function index(Request $request)
    {
        // 1. Pharmacy එක හොයාගන්න
        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();
        if (!$pharmacy) {
            return redirect('/dashboard')->with('error', 'Pharmacy not found.');
        }

        // 2. Orders Query එක හදාගන්න
        $query = Order::with(['customer', 'items.medicine'])
                    ->where('pharmacy_id', $pharmacy->id)
                    ->latest();

        // 3. Filters (Search, Status, Type)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function ($cust) use ($search) {
                      $cust->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('type') && $request->type != '') {
            $query->where('delivery_type', $request->type);
        }

        $orders = $query->paginate(10);
        $orders->appends($request->query());

        // 4. Statistics ගණනය කිරීම
        $totalOrders = Order::where('pharmacy_id', $pharmacy->id)->count();
        $pending     = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'pending')->count();
        $confirmed   = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'confirmed')->count();
        $processing  = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'processing')->count();
        $dispatched  = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'dispatched')->count();
        $delivered   = Order::where('pharmacy_id', $pharmacy->id)->where('status', 'delivered')->count();

        return view('pharmacy-orders', compact(
            'orders', 'totalOrders', 'pending', 'confirmed', 
            'processing', 'dispatched', 'delivered'
        ));
    }

    // 🟢 Status Update කරන කොටස
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,dispatched,delivered,cancelled'
        ]);

        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();
        $order = Order::where('id', $id)->where('pharmacy_id', $pharmacy->id)->firstOrFail();
        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Order status updated successfully!']);
    }
}