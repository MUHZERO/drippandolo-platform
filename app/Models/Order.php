<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_name',
        'product_image',
        'size',
        'customer_name',
        'customer_phone',
        'customer_address',
        'price',
        'status',
        'tracking_number',
        'shopify_order_id',
        'notes',
        'confirmation_price_id',
        'confirmed_price',
        'operator_id',
        'fornissure_id',
        'notified_at',
    ];

    // 🔹 Relationships
    public function confirmationPrice()
    {
        return $this->belongsTo(ConfirmationPrice::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function fornissure()
    {
        return $this->belongsTo(User::class, 'fornissure_id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(FornissureInvoice::class, 'fornissure_invoice_order', 'order_id', 'invoice_id');
    }
}
