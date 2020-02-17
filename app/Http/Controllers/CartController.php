<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function deleteProductFromCart(Product $product) {
        // TODO: support sessions for unauthenticated users
        $cart = Cart::where('user_id', \Auth::user()->id)->first();

        CartProduct::where('product_id', $product->id)->where('cart_id', $cart->id)->delete();
    }

    public function addProductIdToCart($product_id, $quantity = 1) {
        $quantityIsAvailable = Product::where([
                              ['id', '=', $product_id],
                              ['quantity', '>=', $quantity],
                              ])->count();
        
                         
        if($quantityIsAvailable) {
            return $this->addProductToCart(Product::find((int)$product_id), (int)$quantity);
        }
        else {
            
            $tmpProductQuantity = Product::find((int)$product_id)->quantity;

            return response()->json(["success"=> "false", 
                                     "title" => "There is currently not enough inventory in stock.  Please choose ".$tmpProductQuantity." or less!"
                                     ]);
        }
    }

    public function addProductToCart(Product $product, $quantity = 1) {
        if(Auth::check()) {

            $cart = Cart::where('user_id', \Auth::user()->id)->count() == 1
                ? Cart::where('user_id', \Auth::user()->id)->first()
                : new Cart();

            // If the cart doesn't exist yet for the user, add it.
            if (!$cart->user_id) {
                $cart->user()->associate(\Auth::user());
                $cart->save();
            }
        }
        else {
            if(!session('id')) {
                session(['id' => md5(now())]);

                try {
                    $cart = new Cart();
                    $cart->session_id = session('id');
                    $cart->save();
                }
                catch(\Exception $e){
                    // Do nothing.
                }
            }
            else {
                // TODO: come back and refactor this logic as new Cart() will do nothing
                $cart = Cart::where('session_id', session('id'))->count() > 0
                    ? Cart::where('session_id', session('id'))->first()
                    : new Cart();
            }
        }

        if($this->productAlreadyExistsInCart($product, $cart)) {
            $this->incrementCartProductQuantity($product, $cart, $quantity);
        }
        else {
            $this->associateCartProductWithCart($product, $cart, $quantity);
        }

        // Updates last_updated timestamp.
        $cart->touch();
    }

    protected function productAlreadyExistsInCart(Product $product, Cart $cart) {
        return (bool)CartProduct::where('product_id', $product->id)->where('cart_id', $cart->id)->count();
    }

    protected function incrementCartProductQuantity(Product $product, Cart $cart, $quantity) {
        $cart_product = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->first();

        $cart_product->quantity += $quantity;

        $cart_product->save();
    }

    protected function associateCartProductWithCart(Product $product, Cart $cart, $quantity) {
        $cart_product = new CartProduct();
        $cart_product->product()->associate($product);
        $cart_product->quantity = $quantity;
        $cart_product->cart()->associate($cart);

        $cart_product->save();

        $cart->cart_products->add($cart_product);

        $cart->save();
    }

    public function index()
    {
        return View::make('cart');
    }

    public function getCartData() {
        return json_encode(['success' => true, 'payload' => Cart::getCart()], JSON_UNESCAPED_SLASHES);
    }

    public function cartPreview() {
        $_cart = new Cart();

        if (Auth::check()) {
            $cart = Cart::with(['cart_products', 'cart_products.product'])
                ->where('user_id', Auth::id())
                ->first();

            if(!$cart) {
                $cart = new Cart();
                $cart->user_id = Auth::id();
                $cart->save();

                $cart = Cart::with(['cart_products', 'cart_products.product'])
                    ->where('user_id', Auth::id())
                    ->first();
            }

            $total = 0;

            foreach ($cart->cart_products as $cp) {
                $total += $cp->product->price * $cp->quantity;
            }

            $_cart->quantity = $cart->cart_products->count();
            $_cart->total = $total;
        } else {
            if(session()->has('id')) {
                $cart = Cart::with(['cart_products', 'cart_products.product'])
                    ->where('session_id', session('id'))
                    ->first();
            }
            else {
                session(['id' => md5(now())]);

                try {
                    $cart = new Cart();
                    $cart->session_id = session('id');
                    $cart->save();
                }
                catch(\Exception $e){
                    // Do nothing.
                }

                $cart = Cart::with(['cart_products', 'cart_products.product'])
                    ->where('session_id', session('id'))
                    ->first();
            }

            $total = 0;

            foreach ($cart->cart_products as $cp) {
                $total += $cp->product->price * $cp->quantity;
            }

            $_cart->quantity = $cart->cart_products->count();
            $_cart->total = $total;
        }

        return json_encode(['success' => true, 'payload' => $_cart], JSON_UNESCAPED_SLASHES);
    }
}
