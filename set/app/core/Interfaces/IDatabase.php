<?php
namespace Mapathe\Interface;

use PDO;

interface IDatabase {
    public function getConnexion(): PDO;
}