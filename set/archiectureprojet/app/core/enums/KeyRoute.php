<?php
namespace Mapathe\Enums;

enum KeyRoute: string {
    case CONTROLLER = "CONTROLLER";
    case METHOD = "METHOD";
    case MIDDLEWARE = "MIDDLEWARE";
    case HTTP_METHOD = 'HTTP_METHOD';
}