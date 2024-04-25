<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Util/CustomRouter.php';
require_once __DIR__ . '/vendor/autoload.php';

$routes = CustomRouter::getRoutes();

$router = new CustomRouter();
$router->handleRequest($routes);
