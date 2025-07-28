<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;


class Seeder
{
    private static ?PDO $pdo = null;

    private static function connect()
    {
        if (self::$pdo === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();

             self::$pdo = new \PDO(getenv('dsn'),
            getenv('DB_USER'),
              getenv('DB_PASSWORD'));
            
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public static function run()
    {
        $cloud = require __DIR__ . '/../app/config/cloudinary.php';

        Configuration::instance([
            'cloud' => [
                'cloud_name' => $cloud['cloud_name'],
                'api_key' => $cloud['api_key'],
                'api_secret' => $cloud['api_secret'],
            ],
            'url' => ['secure' => true]
        ]);

        $cloudinary = new Cloudinary(Configuration::instance());

        $citoyens = [
            [
                'nom' => 'Diop',
                'prenom' => 'sidi',
                'numerocni' => '1234567890100',
                'photoIdentite' => 'photo_identite1.png',
                'lieuNaiss' => 'Dakar',
                'dateNaiss' => '1980-01-01',
            ]

        ];

        self::connect();

        foreach ($citoyens as $citoyen) {
            try {
                $imagePathIdentite = __DIR__ . '/images/' . $citoyen['photoIdentite'];
                $uploadIdentite = $cloudinary->uploadApi()->upload($imagePathIdentite, ['folder' => 'cni/recto']);
                $urlIdentite = $uploadIdentite['secure_url'];

                $stmt = self::$pdo->prepare("
                    INSERT INTO citoyen (nom, prenom, numerocni, photoIdentite, lieuNaiss, dateNaiss)
                    VALUES (:nom, :prenom, :numerocni, :photoIdentite, :lieuNaiss, :dateNaiss)
                ");

                $stmt->execute([
                    'nom' => $citoyen['nom'],
                    'prenom' => $citoyen['prenom'],
                    'numerocni' => $citoyen['numerocni'],
                    'photoIdentite' => $urlIdentite,
                    'lieuNaiss' => $citoyen['lieuNaiss'],
                    'dateNaiss' => $citoyen['dateNaiss'],
                ]);
            } catch (Exception $e) {
                echo 'Erreur lors de l\'upload ou insertion : ', $e->getMessage(), PHP_EOL;
            }
        }
    }
}

Seeder::run();
