<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmationPrice extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
