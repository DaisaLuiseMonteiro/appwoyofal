<?php

namespace Mapathe;

use Mapathe\Abstract\AbstractController;
use Mapathe\Enums\ClassName;

class ErrorController extends AbstractController
{
    public function _404(): void {
        echo '404';
    }
}
