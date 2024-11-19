<?php

use Farhanisty\Vetran\Application;

require_once (__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new Application();
$route = $app->getRoute();

$route->get('api/menu', function () {
    echo 'hello world';
});
