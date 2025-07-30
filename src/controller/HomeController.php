<?php

namespace App\Controller;

class HomeController 
{
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        
        $apiInfo = [
            'app' => 'AppWoyofal',
            'description' => 'API de prépaiement électricité Senelec',
            'version' => '1.0.0',
            'status' => 'active',
            'endpoints' => [
                // API Woyofal principale
                [
                    'method' => 'GET',
                    'url' => '/api/woyofal/test-achat',
                    'description' => 'Test d\'achat de crédit électrique',
                    'parameters' => [
                        'numero_compteur' => 'Numéro du compteur électrique',
                        'montant' => 'Montant en FCFA'
                    ]
                ],
                [
                    'method' => 'POST', 
                    'url' => '/api/woyofal/acheter',
                    'description' => 'Acheter du crédit électrique',
                    'body' => [
                        'numero_compteur' => 'string - Numéro du compteur',
                        'montant' => 'number - Montant en FCFA'
                    ]
                ],
                [
                    'method' => 'GET',
                    'url' => '/api/woyofal/compteur/{numero}',
                    'description' => 'Vérifier un compteur électrique (local puis Maxit)',
                    'parameters' => [
                        'numero' => 'Numéro du compteur à vérifier'
                    ]
                ],
                
                // API Maxit Integration
                [
                    'method' => 'GET',
                    'url' => '/api/maxit/health',
                    'description' => 'Vérifier la connectivité avec l\'API Maxit'
                ],
                [
                    'method' => 'GET',
                    'url' => '/api/maxit/compteur/{numero}',
                    'description' => 'Rechercher un compteur directement dans Maxit',
                    'parameters' => [
                        'numero' => 'Numéro du compteur à rechercher'
                    ]
                ],
                [
                    'method' => 'POST',
                    'url' => '/api/maxit/sync/{numero}',
                    'description' => 'Synchroniser un compteur depuis Maxit vers la base locale',
                    'parameters' => [
                        'numero' => 'Numéro du compteur à synchroniser'
                    ]
                ],
                [
                    'method' => 'POST',
                    'url' => '/api/maxit/search',
                    'description' => 'Recherche multiple de compteurs dans Maxit',
                    'body' => [
                        'numero' => 'string (optionnel) - Numéro du compteur',
                        'client_nom' => 'string (optionnel) - Nom du client',
                        'client_telephone' => 'string (optionnel) - Téléphone du client',
                        'actif' => 'boolean (optionnel) - Statut actif/inactif'
                    ]
                ]
            ],
            'examples' => [
                'test_achat' => 'GET /api/woyofal/test-achat?numero_compteur=CPT123456&montant=1000',
                'verifier_compteur' => 'GET /api/woyofal/compteur/CPT123456'
            ]
        ];
        
        echo json_encode($apiInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    public function health(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        
        echo json_encode([
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'database' => $this->checkDatabase()
        ], JSON_PRETTY_PRINT);
    }
    
    private function checkDatabase(): string
    {
        try {
            // Simple database connection test
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $port = $_ENV['DB_PORT'] ?? '5432';
            $dbname = $_ENV['DB_NAME'] ?? 'pgdbDaf';
            $username = $_ENV['DB_USER'] ?? 'postgres';
            $password = $_ENV['DB_PASSWORD'] ?? 'postgrespsw';
            
            if (isset($_ENV['DATABASE_URL'])) {
                $url = parse_url($_ENV['DATABASE_URL']);
                $host = $url['host'];
                $port = $url['port'] ?? 5432;
                $dbname = ltrim($url['path'], '/');
                $username = $url['user'];
                $password = $url['pass'];
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
            } else {
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            }
            
            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 5
            ]);
            
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }
}
