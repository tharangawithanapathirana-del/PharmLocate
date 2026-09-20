<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTip extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}