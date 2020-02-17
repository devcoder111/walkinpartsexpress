<?php

namespace App\Services;

use GuzzleHttp\Client;

//TODO: THIS SHOULD BE A SINGLETON CLASS WITH A FACADE INSTEAD OF HAVING STATIC METHODS

class ApiService
{
    public static function response($path, $httpMethod = 'GET', $data = false)
    {
        $client = new Client();
        $request = $client->request($httpMethod, env('IB_API_ENDPOINT') . $path, [
            'auth' => [
                env('IB_API_USERNAME'),
                env('IB_API_PASSWORD'),
            ],
            'timeout' => 25,
            'json' => $data
        ]);


        $response = json_decode($request->getBody()->getContents());

        return $response;
    }

    public static function getWebCategories()
    {
        return self::response("PartsExpressCategories");
    }

    public static function getProducts()
    {
        return self::response("MasterParts");
    }

    public static function getProduct($id)
    {
        return self::response("MasterPart/{$id}");
    }

    public static function initiateOrder($orderData) {

        return self::response("PartsExpressOrders", "POST", $orderData);
    }
}
