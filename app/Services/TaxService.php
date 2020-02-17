<?php

namespace App\Services;

use App\Cart;
use App\Config;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TaxJar\Client as TaxJarClient;
use GuzzleHttp\Client;

class TaxService implements TaxInterface
{
    protected $client;
    protected $cart;
    protected $toZip;
    protected $toState;
    protected $fromZip;
    protected $fromState;
    protected $cartSubtotal;

    public function __construct()
    {
        $this->client = TaxJarClient::withApiKey(env('TAXJAR_API_TOKEN'));

        if(Auth::check())
        {
            $this->cart = Auth::user()->cart;
        }
        else {
            $this->cart = Cart::where('session_id', session('id'))->first();
        }

        // TODO: probably should set these as environment variables, or write other logic to see where items are shipped from.
        $this->fromState = 'OR';
        $this->fromZip = '97030';

        $this->cartSubtotal = 0.00;
    }

    public function getTaxCost($toState, $toZip) {
        $this->cartSubtotal = 0.00;

        if(is_numeric($toState)) {
            $toState = State::find($toState)->abbreviation;
        }

        $this->toState = $toState;
        $this->toZip = $toZip;

        $line_items = collect();

        // Save on TaxJar API limit requests by not calculating the taxes if the cart is empty; simply return $0.00.
        if (empty($this->cart->cart_products)) {
           return 0.00;
        }

        foreach($this->cart->cart_products as $cart_product) {
            $line_items->push(collect(['quantity' => $cart_product->quantity, 'unit_price' => $cart_product->product->price])->toArray());
            $this->cartSubtotal += $cart_product->product->price * $cart_product->quantity;
        }

        // The tax hash is checked to see if the user had details in their cart change so that it doesn't calculate it again multiple times.
        // This saves on rate throttling of API calls to TaxJar.
        if(!$this->taxHashHasChanged()) {
            return $this->cart->tax_cost;
        }

        // This counts as 1 API call.
        $order_taxes = $this->client->taxForOrder([
            'from_country' => 'US',
            'from_zip' => $this->fromZip,
            'from_state' => $this->fromState,
            'to_country' => 'US',
            'to_zip' => $this->toZip,
            'to_state' => $this->toState,
            'amount' => $this->cartSubtotal,
            // TODO: shipping
            'shipping' => 0.0,
            'line_items' => $line_items->toArray()
        ]);

        // Increment monthly request count config variable in DB.
        $config = Config::where('key', 'taxjar_monthly_request_count')->first();
        $config->value = (int)++$config->value;
        $config->save();

        // Save cached tax data to the cart
        $this->saveTaxToCart($order_taxes->amount_to_collect);

        return $order_taxes->amount_to_collect;
    }

    public function saveTaxToCart($tax_cost) {
        if($this->taxHashHasChanged()) {
            $this->cart->tax_cost = $tax_cost;
            $this->cart->tax_hash = $this->generateTaxHash();
            $this->cart->save();
        }
    }

    // Generate a unique md5 hash based off of the subtotal and the to/from states and zip codes;
    //   This is used to prevent the API from being called again if multiple requests for same amount and same zip are called.
    //     helps with throttling the limit on how many monthly API calls.
    protected function generateTaxHash() {
        return md5("Subtotal: {$this->cartSubtotal}; FromZip: {$this->fromZip}; FromState: {$this->fromState}; ToZip: {$this->toZip}; ToState: {$this->toState};");
    }

    protected function taxHashHasChanged() {
        if($this->cart->tax_hash !== $this->generateTaxHash()) {
            return true;
        }

        return false;
    }
}
