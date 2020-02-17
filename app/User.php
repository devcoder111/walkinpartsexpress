<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
// TODO: implement below
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart() {
        return $this->hasOne(Cart::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function moveGuestCartToUserCart() {
        // This code moves session carts over to user cart if necessary.
        if (session('id')) {

            $sessionCart = Cart::with(['cart_products'])->where('session_id', session('id'))->first();

            // Don't replace user's cart if their logged out session cart has no products in it.
            if($sessionCart->cart_products->count() > 0) {

                // If user already has a cart, delete it and the CartProducts before moving session cart over to them.
                if (Cart::where('user_id', $this->id)->count() > 0) {
                    $cart = Cart::where('user_id', $this->id)->first();

                    // Delete all cart products associated with their cart;
                    // TODO: this could be handled by a CASCADE DELETE, and should be...
                    foreach (CartProduct::where('cart_id', $cart->id)->get() as $cp) {
                        $cp->delete();
                    }

                    // Delete the cart.
                    $cart->delete();
                }

                // Update the cart to set session_id to null and user id to the logged in user's id, then save it.
                $cart = Cart::where('session_id', session('id'))->first();
                $cart->session_id = null;
                $cart->user_id = $this->id;
                $cart->save();

                session(['id' => null, 'cart_id' => null]);
            }
        }
    }
}
