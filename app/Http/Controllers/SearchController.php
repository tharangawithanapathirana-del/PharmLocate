<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
{
    $query    = $request->get('q', '');
    $medicines  = collect();
    $pharmacies = collect();
    $pharmaciesJson = '[]';

    if ($query) {
        $medicines = Medicine::where('name', 'like', "%{$query}%")
            ->orWhere('generic_name', 'like', "%{$query}%")
            ->with('pharmacy')
            ->get();

        $pharmacyIds = $medicines->pluck('pharmacy_id')->unique();
        $pharmacies  = Pharmacy::whereIn('id', $pharmacyIds)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $pharmaciesJson = $pharmacies->map(function($p) {
            return [
                'id'      => $p->id,
                'name'    => $p->name,
                'address' => $p->address ?? '',
                'phone'   => $p->phone ?? '',
                'lat'     => (float)$p->latitude,
                'lng'     => (float)$p->longitude,
            ];
        })->values()->toJson();
    }

    return view('search', compact('query', 'medicines', 'pharmacies', 'pharmaciesJson'));
}
}