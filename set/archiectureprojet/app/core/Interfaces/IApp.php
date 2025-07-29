<?php
namespace Mapathe\Interface;

use Mapathe\Enums\ClassName;

interface IApp {
    public static function  getDependencie(ClassName $className): mixed;
}