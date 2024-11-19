<?php

use Farhanisty\DonateloBackend\Repositories\MenuRepositoryImpl;
use Farhanisty\Vetran\Facades\Response;
use Farhanisty\Vetran\Application;

require_once (__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new Application();
$route = $app->getRoute();

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
        ->setBody($menus)
        ->build();
});
