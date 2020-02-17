<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = ['order_status'];

    const STATUS_RECEIVED = 1;
    const STATUS_PAYMENT_PENDING = 2;
    const STATUS_PAYMENT_AUTHORIZED = 3;
    const STATUS_ORDER_PROCESSING = 4;
    const STATUS_ORDER_SHIPPED = 5;
    const STATUS_ORDER_CANCELED = 6;
    const STATUS_NOT_YET_RECEIVED = 7;

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
