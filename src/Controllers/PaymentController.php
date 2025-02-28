<?php

namespace Ollyo\Task\Controllers;

use Ollyo\Task\Services\PaypalService;

class PaymentController
{
    private $products = [
        [
            'name' => 'Minimalist Leather Backpack',
            'image' => BASE_URL . '/resources/images/backpack.webp',
            'qty' => 1,
            'price' => 120,
        ],
        [
            'name' => 'Wireless Noise-Canceling Headphones',
            'image' => BASE_URL . '/resources/images/headphone.jpg',
            'qty' => 1,
            'price' => 250,
        ],
        [
            'name' => 'Smart Fitness Watch',
            'image' => BASE_URL . '/resources/images/watch.webp',
            'qty' => 1,
            'price' => 199,
        ],
        [
            'name' => 'Portable Bluetooth Speaker',
            'image' => BASE_URL . '/resources/images/speaker.webp',
            'qty' => 1,
            'price' => 89,
        ],
    ];

    private $shippingCost = 10;

    public function index()
    {
        $data = [
            'products' => $this->products,
            'shipping_cost' => $this->shippingCost,
            'address' => [
                'name' => 'Sherlock Holmes',
                'email' => 'sherlock@example.com',
                'address' => '221B Baker Street, London, England',
                'city' => 'London',
                'post_code' => 'NW16XE',
            ]
        ];

        return view('checkout', $data);
    }

    public function checkout($request)
    {
        $subtotal = 0;
        foreach ($this->products as $product) {
            $subtotal += $product['qty'] * $product['price'];
        }

        $total = $subtotal + $this->shippingCost;
        
        $paypal = PaypalService::instance();
        header('Location: ' . $paypal->createOrder($total)?->links[1]->href);
    }

    public function confirm($request)
    {
        if (isset($request['cancel']) || !isset($request['token'])) {
            return view('failed', []);
        }

        return view('success', []);
    }
}
