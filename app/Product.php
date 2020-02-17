<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['description', 'deleted', 'api_web_category_id'];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function web_category() {
        return $this->belongsTo(WebCategory::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function cart_products() {
        return $this->hasMany(CartProduct::class);
    }

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }
}
