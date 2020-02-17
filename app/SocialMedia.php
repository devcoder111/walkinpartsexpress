<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = ['social_name', 'target_url'];
}
