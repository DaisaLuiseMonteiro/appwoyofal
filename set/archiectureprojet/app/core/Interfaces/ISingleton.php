<?php
namespace Mapathe\Interface;

interface ISingleton {
    public static function getInstance(): static;
}