<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\PharmacyOrderMail;
use App\Mail\OrderVerified;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Order පිටුව පෙන්වීම (GET)
    public function index(Request $request)
    {
        $id = $request->query('id');
        if (!$id) {
            return redirect('/search');
        }

        $medicine = Medicine::with('pharmacy')->findOrFail($id);
        return view('order', compact('medicine'));
    }

    // Order එක Save කිරීම (POST)
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'medicine_id'   => 'required|exists:medicines,id',
            'quantity'      => 'required|integer|min:1',
            'delivery_type' => 'required|in:delivery,pickup',
            'address'       => 'required_if:delivery_type,delivery|string',
            'prescription'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes'         => 'nullable|string',
        ]);

        // 2. Medicine සහ Pharmacy ගන්න
        $medicine = Medicine::with('pharmacy')->findOrFail($request->medicine_id);
        $pharmacy = $medicine->pharmacy;
        $totalAmount = $medicine->price * $request->quantity;

        // 3. Order එක Database එකට Save කිරීම
        $order = Order::create([
            'customer_id'     => Auth::id(),
            'pharmacy_id'     => $pharmacy->id,
            'total_amount'    => $totalAmount,
            'delivery_address' => $request->delivery_type === 'delivery' ? $request->address : null,
            'delivery_type'   => $request->delivery_type,
            'status'          => 'pending',
            'notes'           => $request->notes,
        ]);

        // Prescription එක Upload කිරීම
        if ($request->hasFile('prescription')) {
            $path = $request->file('prescription')->store('prescriptions', 'public');
            $order->prescription_image = $path;
            $order->save();
        }

        // 4. Order Item එක Save කිරීම
        OrderItem::create([
            'order_id'    => $order->id,
            'medicine_id' => $medicine->id,
            'quantity'    => $request->quantity,
            'price'       => $medicine->price,
        ]);

         $medicine->decrement('quantity', $request->quantity);

        // 5. 📧 Email යැවීම (Customer සහ Pharmacy)
        $customer = Auth::user();
        $pharmacyEmail = $pharmacy->email;

        // A. Customer ට Email යවන්න (Order Confirmation)
        try {
                Mail::to($customer->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                Log::error("Customer Mail Error: " . $e->getMessage()); // 🟢 මෙතනට Log එක එකතු කරන්න
            }

            // B. Pharmacy ට Email යවන්න
            if ($pharmacyEmail) {
                try {
                    Mail::to($pharmacyEmail)->send(new PharmacyOrderMail($order));
                } catch (\Exception $e) {
                    Log::error("Pharmacy Mail Error: " . $e->getMessage()); // 🟢 මෙතනට Log එක එකතු කරන්න
                }
            }
             // 6. Success පණිවිඩය සමඟ Dashboard එකට Redirect කිරීම
                 return redirect('/customer-dashboard')->with('success', 'Order placed successfully! Check your email for confirmation.');
    }

         public function cancel($id)
    {
        $order = Order::where('id', $id)
                      ->where('customer_id', Auth::id())
                      ->where('status', 'pending')
                      ->firstOrFail();

        $medicine = $order->items->first()->medicine;
        if ($medicine) {
            $medicine->increment('quantity', $order->items->first()->quantity);
        }

        $order->delete();

        return redirect()->back()->with('success', 'Order cancelled successfully!');
    }

    public function verifyPrescription($id)
{
    $order = Order::where('id', $id)->where('status', 'pending')->firstOrFail();

    // 1. Status එක Processing ලෙස Update කරන්න
    $order->update(['status' => 'processing']);

    // 2. 🟢 Customer ට Email එක යවන්න
    try {
        Mail::to($order->customer->email)->send(new OrderVerified($order));
    } catch (\Exception $e) {
        Log::error("Order Verified Mail Error: " . $e->getMessage());
    }

    return response()->json([
        'success' => true, 
        'message' => 'Prescription verified! Order is now Processing.'
    ]);
}
}