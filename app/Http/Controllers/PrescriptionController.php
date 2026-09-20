<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        // 1. දැනට ලොග් වී ඉන්න Pharmacy එක හොයාගන්න
        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();

        if (!$pharmacy) {
            return redirect('/dashboard')->with('error', 'Pharmacy not found.');
        }

        // 2. Prescription තියෙන (prescription_image != null) Orders ටික ගන්න
        $query = Order::with(['customer', 'items.medicine'])
                    ->where('pharmacy_id', $pharmacy->id)
                    ->whereNotNull('prescription_image');

        // 3. Filter (Status අනුව පෙරීම)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $prescriptions = $query->latest()->paginate(10);

        // 4. Statistics (Total, Pending, Verified)
        $totalPrescriptions = Order::where('pharmacy_id', $pharmacy->id)->whereNotNull('prescription_image')->count();
        $pendingPrescriptions = Order::where('pharmacy_id', $pharmacy->id)->whereNotNull('prescription_image')->where('status', 'pending')->count();
        $verifiedPrescriptions = Order::where('pharmacy_id', $pharmacy->id)->whereNotNull('prescription_image')->where('status', '!=', 'pending')->count();

        return view('prescriptions', compact('prescriptions', 'totalPrescriptions', 'pendingPrescriptions', 'verifiedPrescriptions'));
    }
}