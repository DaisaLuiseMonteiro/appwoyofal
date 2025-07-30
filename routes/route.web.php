<?php

use Mapathe\Enums\KeyRoute;
use Mapathe\ErrorController;
use App\Controller\WoyofalController;
use App\Controller\HomeController;
use App\Controller\MaxitController;

$routes = [
    '/' => [
        KeyRoute::CONTROLLER->value => HomeController::class,
        KeyRoute::METHOD->value => 'index',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    '/health' => [
        KeyRoute::CONTROLLER->value => HomeController::class,
        KeyRoute::METHOD->value => 'health',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
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
    '/api/woyofal/compteurs' => [
        KeyRoute::CONTROLLER->value => WoyofalController::class,
        KeyRoute::METHOD->value => 'listerCompteurs',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    '/api/woyofal/compteur/{numero}' => [
        KeyRoute::CONTROLLER->value => WoyofalController::class,
        KeyRoute::METHOD->value => 'verifierCompteur',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    
    // Routes pour l'intégration Maxit
    '/api/maxit/health' => [
        KeyRoute::CONTROLLER->value => MaxitController::class,
        KeyRoute::METHOD->value => 'health',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    '/api/maxit/compteur/{numero}' => [
        KeyRoute::CONTROLLER->value => MaxitController::class,
        KeyRoute::METHOD->value => 'searchCompteur',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'GET'
    ],
    '/api/maxit/sync/{numero}' => [
        KeyRoute::CONTROLLER->value => MaxitController::class,
        KeyRoute::METHOD->value => 'syncCompteur',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'POST'
    ],
    '/api/maxit/search' => [
        KeyRoute::CONTROLLER->value => MaxitController::class,
        KeyRoute::METHOD->value => 'searchMultiple',
        KeyRoute::MIDDLEWARE->value => [],
        KeyRoute::HTTP_METHOD->value => 'POST'
    ]
];