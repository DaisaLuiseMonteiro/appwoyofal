<?php
namespace Mapathe\Abstract;

use Mapathe\Response;

abstract class AbstractApi {
    protected function renderJson(Response $response): void
    {
        http_response_code(201);
        header('Content-Type: application/json');
        echo $response->toJson();
    }
}