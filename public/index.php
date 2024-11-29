<?php

use Farhanisty\DonateloBackend\Repositories\MenuRepositoryFactory;
use Farhanisty\DonateloBackend\Repositories\MenuRepositoryImpl;
use Farhanisty\DonateloBackend\Repositories\TokenRepositoryFactory;
use Farhanisty\DonateloBackend\Services\HandlePaymentService;
use Farhanisty\Vetran\Facades\Response;
use Farhanisty\Vetran\Application;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once (__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new Application();
$route = $app->getRoute();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$route->get('api/menu', function () use ($app) {
    $menuRepository = new MenuRepositoryImpl();

    $menus = $menuRepository->getAll();

    $response = $app->getResponse()->responseJson();

    if (!count($menus)) {
        $response
            ->setStatus(Response::NO_CONTENT)
            ->build();

        return;
    }

    $menus = array_map(function ($menu) {
        return $menu->toArray();
    }, $menus);

    $response
        ->setStatus(Response::OK)
        ->setBody([
            'status' => true,
            'message' => 'OK',
            'data' => $menus
        ])
        ->build();
});

$route->get('api/menu/:id', function () use ($app) {
    $response = $app->getResponse()->responseJson();

    $id = $app->getRoute()->input()->inputParameter()['id'];

    $menuRepository = MenuRepositoryFactory::getInstance();

    $menu = $menuRepository->getById($id);

    if (!$menu) {
        $response
            ->setStatus(Response::NOT_FOUND)
            ->setBody([
                'status' => false,
                'message' => 'NOT FOUND'
            ])
            ->build();

        return;
    }

    $response
        ->setStatus(Response::OK)
        ->setBody([
            'status' => true,
            'message' => 'OK',
            'data' => $menu->toArray()
        ])
        ->build();
});

$route->post('api/payment', function () use ($app) {
    $handlePaymentService = new HandlePaymentService($app, MenuRepositoryFactory::getInstance(), TokenRepositoryFactory::getInstance());
    $handlePaymentService->handle();
});

$route->post('api/validate-token', function () use ($app, $route) {
    $inputBody = $route->input()->jsonBody();
    $response = $app->getResponse()->responseJson();

    if (!$inputBody) {
        return $response
            ->setStatus(Response::BAD_REQUEST)
            ->setBody([
                'status' => false,
                'message' => 'BAD REQUEST',
            ])
            ->build();
    }

    try {
        $decoded = JWT::decode($inputBody['token'], new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
    } catch (Firebase\JWT\SignatureInvalidException $e) {
        return $response
            ->setStatus(Response::UNAUTHORIZED)
            ->setBody([
                'status' => false,
                'message' => 'UNAUTHORIZED',
            ])
            ->build();
    }

    $tokenRepository = TokenRepositoryFactory::getInstance();

    $token = $tokenRepository->getByToken($inputBody['token']);

    $status = [];
    if ($token->isActive) {
        $status['is_active'] = true;
    } else {
        $status['is_active'] = false;
        $status['exchange_date'] = $token->updatedAt;
    }
    $rawOrders = $decoded->data->orders;
    $orderIds = array_map(function ($order) {
        return $order->id;
    }, $rawOrders);

    $menuRepository = MenuRepositoryFactory::getInstance();

    $menus = $menuRepository->getWhereIdIn($orderIds);

    $orders = [];

    foreach ($rawOrders as $key => $order) {
        $orders[$key]['quantity'] = $order->quantity;
        $orders[$key]['menu'] = $menus[$key]->toArray();
    }

    $data = [
        'status' => $status,
        'purchase_date' => $decoded->data->purchase_date,
        'payment_amount' => $decoded->data->payment_amount,
        'customer' => [
            'name' => $decoded->data->customer->name,
            'email' => $decoded->data->customer->email
        ],
        'orders' => $orders
    ];

    return $response
        ->setStatus(Response::OK)
        ->setBody([
            'status' => true,
            'message' => 'OK',
            'data' => $data
        ])
        ->build();
});
