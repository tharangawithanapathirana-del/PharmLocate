<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
   public function index()
{
    $pharmacy = Pharmacy::where('user_id', Auth::id())->first();
    $medicines = $pharmacy ? Medicine::where('pharmacy_id', $pharmacy->id)->latest()->get() : collect();
    
    // 🟢 මෙතනට Orders ගණන ගන්නා කේතය එකතු කරන්න
    $totalOrders = $pharmacy ? Order::where('pharmacy_id', $pharmacy->id)->count() : 0;

    return view('stock-management', compact('medicines', 'pharmacy', 'totalOrders'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'generic_name'   => ['nullable', 'string', 'max:255'],
            'category'       => ['required', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'quantity'       => ['required', 'integer', 'min:0'],
            'expiry_date'    => ['nullable', 'date'],
            'is_rx_required' => ['nullable'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'max:2048'],
            'dosage_form' => ['nullable', 'string'],
            'strength' => ['nullable', 'string'],
             'manufacturer' => ['nullable', 'string'],
        ]);

        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medicines', 'public');
        }

        Medicine::create([
            'pharmacy_id'    => $pharmacy->id,
            'name'           => $request->name,
            'generic_name'   => $request->generic_name,
            'category'       => $request->category,
            'price'          => $request->price,
            'quantity'       => $request->quantity,
            'expiry_date'    => $request->expiry_date,
            'is_rx_required' => $request->is_rx_required ? 1 : 0,
            'description'    => $request->description,
            'image'          => $imagePath,
            'dosage_form' => $request->dosage_form,
            'strength' => $request->strength,
            'manufacturer' => $request->manufacturer,
        ]);

        return redirect()->back()->with('success', 'Medicine added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'generic_name'   => ['nullable', 'string', 'max:255'],
            'category'       => ['required', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'quantity'       => ['required', 'integer', 'min:0'],
            'expiry_date'    => ['nullable', 'date'],
            'is_rx_required' => ['nullable'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'max:2048'],
            'dosage_form' => ['nullable', 'string'],
             'strength' => ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string'],
        ]);

        $medicine = Medicine::findOrFail($id);

        $imagePath = $medicine->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update([
            'name'           => $request->name,
            'generic_name'   => $request->generic_name,
            'category'       => $request->category,
            'price'          => $request->price,
            'quantity'       => $request->quantity,
            'expiry_date'    => $request->expiry_date,
            'is_rx_required' => $request->is_rx_required ? 1 : 0,
            'description'    => $request->description,
            'image'          => $imagePath,
            'dosage_form' => $request->dosage_form,
            'strength' => $request->strength,
            'manufacturer' => $request->manufacturer,
        ]);

        return redirect()->back()->with('success', 'Medicine updated successfully!');
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return redirect()->back()->with('success', 'Medicine deleted successfully!');
    }
}