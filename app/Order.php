<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }

    public function shipping_address() {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }

    public function billing_address() {
        return $this->belongsTo(Address::class, 'billing_address_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function payment_gateway() {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function order_status() {
        return $this->belongsTo(OrderStatus::class);
    }
}
