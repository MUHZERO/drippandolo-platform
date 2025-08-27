<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spend extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
    ];


    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
