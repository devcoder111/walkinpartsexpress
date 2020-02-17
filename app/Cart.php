<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{

    protected $fillable = [
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function cart_products() {
        return $this->hasMany(CartProduct::class);
    }

    public static function getCart() {
        if(Auth::check()) {
            $c = Cart::with([
                    'cart_products',
                    'cart_products.product',
                    'cart_products.product.images',
                    'cart_products.product.images.image_thumbnail',
                ])
                ->where('user_id', Auth::id())
                ->first();
        }
        else {
            $c = Cart::with([
                    'cart_products',
                    'cart_products.product',
                    'cart_products.product.images',
                    'cart_products.product.images.image_thumbnail',
                ])
                ->where('session_id', session('id'))
                ->first();

            if(!$c) {
                session(['id' => md5(now())]);

                try {
                    $c = new Cart();
                    $c->session_id = session('id');
                    $c->save();
                }
                catch(\Exception $e){
                    // Do nothing.
                }

                $c = Cart::with([
                    'cart_products',
                    'cart_products.product',
                    'cart_products.product.images',
                    'cart_products.product.images.image_thumbnail',
                ])
                    ->where('session_id', session('id'))
                    ->first();
            }
        }

        return $c;
    }
}
