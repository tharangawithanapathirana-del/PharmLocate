<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PharmacyReportExport;

class ReportController extends Controller
{
    public function index()
    {
        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();
        if (!$pharmacy) {
            return redirect()->back()->with('error', 'Pharmacy not found.');
        }
        return view('pharmacy-reports', compact('pharmacy'));
    }

    public function downloadReport(Request $request)
    {
        $pharmacy = Pharmacy::where('user_id', Auth::id())->first();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(new PharmacyReportExport($pharmacy, $startDate, $endDate), 'pharmacy_report.xlsx');
    }
}