<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['name'];

    const TYPE_AUTHORIZE_DOT_NET = 1;
    const TYPE_PAYPAL = 2;

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
