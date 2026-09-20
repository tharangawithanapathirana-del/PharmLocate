<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'name',
        'generic_name',
        'category',
        'price',
        'quantity',
        'expiry_date',
        'is_rx_required',
        'description',
        'image',
         'dosage_form',
        'strength',
        'manufacturer',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}