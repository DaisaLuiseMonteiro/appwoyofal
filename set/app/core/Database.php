<?php

namespace Mapathe;

use PDO;

class Database extends Singleton
{
    private ?PDO $pdo = null;
    private static array $configDefault = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    public function __construct(string $driver, string $host, $db_port, string $db_name, string $db_user, string $db_password)
    {

        // dd($db_password);
        try {
            $dsn = $driver . ':host=' . $host . ';port=' . $db_port . ';dbname=' . $db_name;
            $this->pdo = new PDO(
                $dsn,
                $db_user,
                $db_password,
                self::$configDefault
            );
        } catch (\PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function getConnexion(): PDO
    {
        return $this->pdo;
    }
}
