<?php

use Mapathe\Enums\KeyRoute;
use Mapathe\ErrorController;
use App\Controller\WoyofalController;

$routes = [
    '/' => [
        KeyRoute::CONTROLLER->value => ErrorController::class,
        KeyRoute::METHOD->value => '_404',
        KeyRoute::MIDDLEWARE->value => []
    ],
    '/404' => [
        KeyRoute::CONTROLLER->value => ErrorController::class,
        KeyRoute::METHOD->value => '_404',
        KeyRoute::MIDDLEWARE->value => []
    ],
    '/api/woyofal/acheter' => [
        KeyRoute::CONTROLLER->value => WoyofalController::class,
        KeyRoute::METHOD->value => 'acheter',
        KeyRoute::MIDDLEWARE->value => [],
         KeyRoute::HTTP_METHOD->value => 'POST'
    ], 
      '/api/woyofal/test-achat' => [
        KeyRoute::CONTROLLER->value => WoyofalController::class,
        KeyRoute::METHOD->value => 'acheter',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    '/api/woyofal/compteur/{numero}' => [
        KeyRoute::CONTROLLER->value => WoyofalController::class,
        KeyRoute::METHOD->value => 'verifierCompteur',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ]
];