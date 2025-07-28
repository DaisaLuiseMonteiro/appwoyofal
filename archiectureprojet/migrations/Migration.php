<?php

require_once  __DIR__ .  '/../vendor/autoload.php';
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


class Migration
{
    private static ?\PDO $pdo = null;

    private static function connect()
    {
        if (self::$pdo === null) {
          
            self::$pdo = new \PDO(getenv('dsn'),
            getenv('DB_USER'),
              getenv('DB_PASSWORD'));
        }
    }

    private static function getQueries(): array {
    $driver = self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'mysql') {
        return [ 
            "CREATE TABLE IF NOT EXISTS citoyen (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            prenom VARCHAR(100) NOT NULL,
            numerocni VARCHAR(20) UNIQUE,
            photoidentite TEXT,
            lieuNaiss varchar(100),
            dateNaiss DATE
        )",
            "CREATE TABLE IF NOT EXISTS journalisation (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                heure TimeStamp DEFAULT CURRENT_TIMESTAMP,
                localisation TEXT,
                ipadress VARCHAR(50),
                status boolean DEFAULT false,
                citoyenId INTEGER UNSIGNED NOT NULL,
                FOREIGN KEY (citoyenId) REFERENCES citoyen(id
            )"
        ];
    } else {
        return [
            "CREATE TABLE IF NOT EXISTS citoyen (
                id SERIAL PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                numerocni VARCHAR(20) UNIQUE,
                photoidentite TEXT,
                lieuNaiss varchar(100),
                dateNaiss DATE
            )",
            "CREATE TABLE IF NOT EXISTS journalisation (
                id SERIAL PRIMARY KEY,
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                heure TimeStamp DEFAULT CURRENT_TIMESTAMP,
                localisation TEXT,
                ipadress VARCHAR(50),
                status boolean DEFAULT false,
                citoyenId INTEGER REFERENCES citoyen(id)
            )"
        ];
    }
}


    public static function up()
{
    self::connect();
    $queries = self::getQueries();

    foreach ($queries as $sql) {
        try {
            self::$pdo->exec($sql);
            echo "Requête exécutée avec succès.\n";
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    echo "Migration terminée avec succès.\n";
}

}

Migration::up();