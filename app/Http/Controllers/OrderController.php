<?php

namespace App\Http\Controllers;

use App\User;
use App\Address;
use App\Cart;
use App\CartProduct;
use App\Order;
use App\OrderProduct;
use App\OrderStatus;
use App\PaymentGateway;
use App\Services\ApiService;
use App\State;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order();
        $order->user_id = \Auth::check() ? \Auth::user()->id : null;
        $order->subtotal_cost = 0;
        $order->tax_cost = 0;
        $order->shipping_cost = 0;
        $order->total_cost = 0;
        $order->api_order_id = 0;
        $order->last_4_cc_number = substr($request->input('ccNumber'), -4);

        // Create the addresses and store them, then associate to order.
        $shippingAddress = new Address();
        $shippingAddress->address_type_id = Address::TYPE_SHIPPING;
        $shippingAddress->first_name = trim($request->input('shippingAddress')['firstName']);
        $shippingAddress->last_name = trim($request->input('shippingAddress')['lastName']);
        $shippingAddress->address_first = trim($request->input('shippingAddress')['address1']);
        $shippingAddress->address_second = trim($request->input('shippingAddress')['address2']) !== '' ? trim($request->input('shippingAddress')['address2']) : null;
        $shippingAddress->city = trim($request->input('shippingAddress')['city']);
        $shippingAddress->state_id = $request->input('shippingAddress')['state'];
        $shippingAddress->zip_code = $request->input('shippingAddress')['zip'];

        $shippingAddress->save();

        $billingAddress = new Address();
        $billingAddress->address_type_id = Address::TYPE_BILLING;
        $billingAddress->first_name = trim($request->input('billingAddress')['firstName']);
        $billingAddress->last_name = trim($request->input('billingAddress')['lastName']);
        $billingAddress->address_first = trim($request->input('billingAddress')['address1']);
        $billingAddress->address_second = trim($request->input('billingAddress')['address2']) !== '' ? trim($request->input('billingAddress')['address2']) : null;
        $billingAddress->city = trim($request->input('billingAddress')['city']);
        $billingAddress->state_id = $request->input('billingAddress')['state'];
        $billingAddress->zip_code = $request->input('billingAddress')['zip'];

        $billingAddress->save();

        $order->shipping_address_id = $shippingAddress->id;
        $order->billing_address_id = $billingAddress->id;

        $order->order_status_id = OrderStatus::STATUS_NOT_YET_RECEIVED;
        $order->payment_gateway_id = PaymentGateway::TYPE_AUTHORIZE_DOT_NET;

        $order->save();

        // Move all items over from cart products to order products, and save them on the order.
        $cart = Cart::getCart();

        foreach($cart->cart_products as $cp) {
            $order_product = new OrderProduct();
            $order_product->order_id = $order->id;
            $order_product->product_id = $cp->product_id;
            $order_product->quantity = $cp->quantity;
            $order_product->price_paid_per_unit = $cp->product->price;

            $order_product->cached_product_name = $cp->product->description;
            // TODO: add image hash from images table
            $order_product->image_hash = md5('TODO FIX ME');
            $order_product->order()->associate($order);
            $order_product->save();

            $order->subtotal_cost += $order_product->quantity * $order_product->price_paid_per_unit;

            CartProduct::destroy($cp->id);
        }

        $cart->destroy();
        session()->flush();

        // TODO: calculate freight and tax costs

        $order->total_cost = $order->subtotal_cost + $order->tax_cost + $order->shipping_cost;

        $order->save();

        $responseIbApi = $this->sendOrderToAPI($order);

        // TODO: try authorize.net
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName("YOURLOGIN");
        $merchantAuthentication->setTransactionKey("YOURKEY");

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);

        return $responseIbApi;
    }

    public function sendOrderToAPI(Order $order) {
        $orderData = new \Stdclass;
        $orderData->CustomerId = 0;
        $orderData->PaymentGatewayId = $order->payment_gateway->id;
        $orderData->OrderDate = date("Y-m-d");
        $orderData->SubTotal = $order->subtotal_cost;
        $orderData->Freight = $order->shipping_cost;
        $orderData->Taxes = $order->tax_cost;
        $orderData->Total = $order->total_cost;
        if($order->order_status->id !== OrderStatus::STATUS_NOT_YET_RECEIVED) {
            $orderData->OrderStatusId = $order->order_status_id;
        }

        //$orderData->CompanyName = !empty($order->company_name) ? $order->company_name : 'N/A';
        //$orderData->FirstName = $order->shipping_address->first_name;
        //$orderData->LastName = $order->shipping_address->last_name;
        $orderData->Email = !empty($order->user) ? $order->user->email : null;

        //TODO: phone
        $orderData->Phone = null;

        // working items
        $orderData->ShipToName = $order->shipping_address->first_name . ' ' . $order->shipping_address->last_name;
        $orderData->ShipToAddress = $order->shipping_address->address_first . ($order->shipping_address->address_second !== null ? ', '.$order->shipping_address->address_second : '');
        $orderData->ShipToCity = $order->shipping_address->city;
        $orderData->ShipToState = State::find($order->shipping_address->state_id)->abbreviation;
        $orderData->ShipToZip = $order->shipping_address->zip_code;

        //TODO: ship to phone number
        $orderData->ShipToPhone = $orderData->Phone;

        //working items
        $orderData->BillToName = $order->billing_address->first_name . ' ' . $order->billing_address->last_name;
        $orderData->BillToAddress = $order->billing_address->address_first . ($order->billing_address->address_second !== null ? ', '.$order->billing_address->address_second : '');
        $orderData->BillToCity = $order->billing_address->city;
        $orderData->BillToState = State::find($order->billing_address->state_id)->abbreviation;
        $orderData->BillToZip = $order->billing_address->zip_code;

        $orderProducts = [];

        foreach($order->order_products as $op) {
            $orderProductData = new \Stdclass;
            $orderProductData->MasterPartId = $op->product->master_part_number;
            $orderProductData->Description = $op->product->description;
            $orderProductData->Quantity = $op->quantity;
            $orderProductData->Price = $op->price_paid_per_unit;
            $orderProductData->Total = $orderProductData->Quantity*$orderProductData->Price;

            $orderProducts[] = $orderProductData;
        }

        $orderData->PartsExpressOrderItems = $orderProducts;

        $response = ApiService::initiateOrder($orderData);

        // If OrderId was passed, update the order with the api_order_id, and the new order status
        if($response->OrderId) {
            $order->api_order_id = $response->OrderId;
            $order->order_status_id = $response->OrderStatusId;
            $order->save();
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    function chargeCreditCard($amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('login id');
        $merchantAuthentication->setTransactionKey('transaction key');

        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4111111111111111");
        $creditCard->setExpirationDate("2038-12");
        $creditCard->setCardCode("123");
        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("10101");
        $order->setDescription("Golf Shirts");
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName("Ellen");
        $customerAddress->setLastName("Johnson");
        $customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress("14 Main Street");
        $customerAddress->setCity("Pecan Springs");
        $customerAddress->setState("TX");
        $customerAddress->setZip("44628");
        $customerAddress->setCountry("USA");
        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId("99999456654");
        $customerData->setEmail("EllenJohnson@example.com");
        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");
        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        $merchantDefinedField1 = new AnetAPI\UserFieldType();
        $merchantDefinedField1->setName("customerLoyaltyNum");
        $merchantDefinedField1->setValue("1128836273");
        $merchantDefinedField2 = new AnetAPI\UserFieldType();
        $merchantDefinedField2->setName("favoriteColor");
        $merchantDefinedField2->setValue("blue");
        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        $transactionRequestType->addToUserFields($merchantDefinedField1);
        $transactionRequestType->addToUserFields($merchantDefinedField2);
        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                }
            }
        } else {
            echo  "No response returned \n";
        }
        return $response;
    }

    function getOrderStatus() {
        $user = \Auth::user();
        $orders = Order::with(['order_status', 'order_products', 'payment_gateway'])->where('user_id', $user->id)->get();        
        
        return response()->json(["order"=>$orders]);
    }
}
