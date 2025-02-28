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
        $this->clientId = config('paypal_client_id', '');
        $this->clientSecret = config('paypal_client_secret', '');

        $this->accessToken = $this->getAccessToken();

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => config('paypal_base_url', ''),
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
