<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FornissureInvoice extends Model
{
    protected $fillable = [
        'fornissure_id',
        'reference',
        'type',
        'period_start',
        'period_end',
        'amount',
        'status',
        'transaction_image'
    ];

    public function fornissure()
    {
        return $this->belongsTo(User::class, 'fornissure_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'fornissure_invoice_order', 'invoice_id', 'order_id');
    }
}
