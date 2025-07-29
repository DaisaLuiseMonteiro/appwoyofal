<?php
use Dotenv\Dotenv;

// Pointer vers la racine du projet de façon absolue
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '5432');
define('DB_DRIVE', $_ENV['DB_DRIVE'] ?? 'pgsql');
define('DB_USER', $_ENV['DB_USER'] ?? 'user');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? 'postgrespsw');
define('DB_NAME', $_ENV['DB_NAME'] ?? $_ENV['DB_DATABASE'] ?? 'dbname');
define('METHODE_INSTANCE_NAME', $_ENV['METHODE_INSTANCE_NAME'] ?? 'getInstance');
// Chemin absolu vers services.yml
define('SERVICES_PATH', $_ENV['SERVICES_PATH'] ?? __DIR__ . '/services.yml');