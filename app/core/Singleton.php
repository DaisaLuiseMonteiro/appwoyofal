<?php
namespace Mapathe;

// app/core/Singleton.php


abstract class Singleton
{
    private static array $instances = [];

    public static function getInstance(...$args): static
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static(...$args);
        }

        return self::$instances[$class];
    }
}


