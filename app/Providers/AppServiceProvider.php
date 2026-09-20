<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\Medicine;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 🟢 Sidebar එකට Data යවන Composer එක
        View::composer('layouts.sidebar', function ($view) {
            // දැන් ලොග් වෙලා ඉන්න User ගේ Pharmacy එක හොයාගන්න
            $pharmacy = Pharmacy::where('user_id', Auth::id())->first();

            if ($pharmacy) {
                $totalOrders = Order::where('pharmacy_id', $pharmacy->id)->count();
                $totalPrescriptions = Order::where('pharmacy_id', $pharmacy->id)
                                            ->whereNotNull('prescription_image')
                                            ->count();
                $totalMedicines = Medicine::where('pharmacy_id', $pharmacy->id)->count();
            } else {
                $totalOrders = 0;
                $totalPrescriptions = 0;
                $totalMedicines = 0;
            }

            // මේ දත්ත Sidebar එකට යවනවා
            $view->with(compact('totalOrders', 'totalPrescriptions', 'totalMedicines'));
        });
    }
}