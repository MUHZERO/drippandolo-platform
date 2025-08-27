<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenu extends Model
{
    //
    //
    protected $fillable = [
        'date',
        'amount',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }
}
