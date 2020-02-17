<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $cartProductId
     * @return \Illuminate\Http\Response
     */
    public function destroy($cartProductId)
    {
        try {
            $cart = Cart::getCart();

            if($cart->cart_products->count() == 0) {
                throw new \Exception('You have no items in your cart!');
            }

            if(!in_array($cartProductId, $cart->cart_products->pluck('id')->toArray())) {
                throw new \Exception('That item is not in your cart!  Please try refreshing the page.');
            }

            CartProduct::find($cartProductId)->delete();

            return json_encode(['success' => true], JSON_UNESCAPED_SLASHES);
        }
        catch(\Exception $e) {
            return json_encode(['success' => false, 'payload' => ['error' => $e->getMessage()]], JSON_UNESCAPED_SLASHES);
        }
    }
}
