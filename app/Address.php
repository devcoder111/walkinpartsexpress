<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    const TYPE_BILLING = 1;
    const TYPE_SHIPPING = 2;

    public function address_type() {
        return $this->belongsTo(AddressType::class);
    }
}
