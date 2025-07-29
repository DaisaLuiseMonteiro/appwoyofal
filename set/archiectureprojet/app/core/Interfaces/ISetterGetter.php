<?php
namespace Mapathe\Interface;

interface ISetterGetter {
    public function __set($name, $value);
    public function __get($name);
}