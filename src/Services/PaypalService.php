<?php

namespace Ollyo\Task\Services;

use Exception;

class PaypalService
{
    private static $instance;
    private $client;
    private $clientId;
    private $clientSecret;
    private $accessToken;

    private function __construct()
    {
        $this->clientId = "AXPZXOlOEUw9xhfUU78Asbqio7Yqp2flOPHDy6CF2AQ3_uRuDfWSyZGfeh3Fp1ltRIctqXa4y9OZwQX4";
        $this->clientSecret = "EODV0Bdpt23S4_WPwXwrmsRxoP5Wp7dDYjOI4to2xYyLXFfmlqIa126e7rV9h0qugHoj77FDlSt3LjNo";

        $this->accessToken = $this->getAccessToken();

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api-m.sandbox.paypal.com',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);
    }

    private function getAccessToken()
    {
        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->request('POST', 'https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'auth' => [
                    $this->clientId,
                    $this->clientSecret,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => ['grant_type' => 'client_credentials']
            ]);

            $body = json_decode($response->getBody()?->getContents());

            if ($response->getStatusCode() == 200) {
                return $body->access_token;
            }

            throw new Exception($body);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function createOrder(string $amount)
    {
        try {
            $res = $this->client->request('POST', '/v2/checkout/orders', [
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            // "reference_id"=> "d9f80740-38f0-11e8-b467-0edd5f89f718b",
                            // 'items' => ['name' => 'Product 1', 'quantity' => 1, 'unit_amount' => ['currency_code' => 'USD', 'value' => 10]],
                            'amount' => ['currency_code' => 'USD', 'value' => $amount],
                        ]
                    ],
                    'application_context' => array(
                        'user_action' => 'PAY_NOW',
                        'return_url' => 'http://127.0.0.1:8000/confirm',
                        'cancel_url' => 'http://127.0.0.1:8000/confirm?cancel=true'
                    )
                ],
            ]);

            return json_decode($res->getBody()->getContents());
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
