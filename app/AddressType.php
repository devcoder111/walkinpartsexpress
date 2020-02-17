<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    protected $fillable = ['type'];

    public function address(){
        return $this->hasMany(Address::class);
    }
}
