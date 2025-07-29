<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ CORRECTION: Utiliser __DIR__ au lieu de chemin relatif
require_once __DIR__ . '/../app/config/bootstrap.php';

use Mapathe\Router;

Router::setRoute($routes);

Router::resolve();