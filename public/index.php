<?php

use Farhanisty\DonateloBackend\Repositories\MenuRepositoryFactory;
use Farhanisty\DonateloBackend\Repositories\MenuRepositoryImpl;
use Farhanisty\Vetran\Facades\Response;
use Farhanisty\Vetran\Application;

require_once (__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new Application();
$route = $app->getRoute();

header('Access-Control-Allow-Origin: *');

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
