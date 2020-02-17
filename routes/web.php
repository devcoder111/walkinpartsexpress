<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


Route::get('/', function () {
    return view('index');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/contact-us', function () {
    return view('contact-us');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/shipping-policy', function () {
    return view('shipping-policy');
});

Route::get('/terms-conditions', function () {
    return view('terms-conditions');
});

Route::get('/returns-refund-cancellation-policy', function () {
    return view('return-refund-and-cancellation-policy');
});

Route::get('/pre-checkout', function () {
    return view('pre-checkout');
});

Route::get('/checkout-success', function () {
    return view('checkout-success');
});

Route::get('/search', function () {
    return view('search');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/api/cart-preview', 'CartController@cartPreview');
Route::get('/api/order-status', 'OrderController@getOrderStatus');

Route::post('add-to-cart/product/{product}/quantity/{quantity}', 'CartController@addProductIdToCart');

Route::resource('product-image', 'ProductImageController');
Route::resource('cart', 'CartController');
Route::get('get-cart-data', 'CartController@getCartData');
Route::resource('cart-product', 'CartProductController');
Route::resource('category', 'CategoryController');
Route::resource('product', 'ProductController');
Route::resource('state', 'StateController');
Route::resource('order', 'OrderController');


Route::get('taxes/{toState}/{toZip}', 'TaxController@getTaxCost');
Route::get('get-aws-s3-base-path', 'EnvironmentVariableController@getAwsS3BasePath');
