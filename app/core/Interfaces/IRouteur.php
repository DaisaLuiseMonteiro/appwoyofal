<?php
namespace Mapathe\Interface;

interface IRouteur {
    public static function resolve(): void;
    public static function setRoute(array $route): void;
}