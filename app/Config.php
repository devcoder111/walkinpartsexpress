<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;

    protected $fillable = ['key', 'value'];
}
