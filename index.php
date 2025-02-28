<?php

use Ollyo\Task\Routes;
use Ollyo\Task\Controllers\PaymentController;

$env = parse_ini_file('.env');
define('ENV', $env);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/config.php';

define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', baseUrl());

Routes::get('/', function () {
    return view('app', []);
});

Routes::get('/checkout', [PaymentController::class, 'index']);

Routes::post('/checkout', [PaymentController::class, 'checkout']);

// Register thank you & payment failed routes with corresponding views here.
Routes::get('/confirm', [PaymentController::class, 'confirm']);

$route = Routes::getInstance();
$route->dispatch();
