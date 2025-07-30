<?php

namespace App\Service;

class MaxitService
{
    private string $maxitApiUrl;
    private string $maxitApiKey;
    private int $timeout;
    private ?string $maxitDbHost;
    private ?string $maxitDbUser;
    private ?string $maxitDbPassword;
    private ?string $maxitDbName;

    public function __construct()
    {
        // Configuration pour API externe Maxit
        $this->maxitApiUrl = $_ENV['MAXIT_API_URL'] ?? '';
        $this->maxitApiKey = $_ENV['MAXIT_API_KEY'] ?? '';
        $this->timeout = (int)($_ENV['MAXIT_TIMEOUT'] ?? 30);
        
        // Configuration pour base de données externe Maxit
        $this->maxitDbHost = $_ENV['MAXIT_DB_HOST'] ?? null;
        $this->maxitDbUser = $_ENV['MAXIT_DB_USER'] ?? null;
        $this->maxitDbPassword = $_ENV['MAXIT_DB_PASSWORD'] ?? null;
        $this->maxitDbName = $_ENV['MAXIT_DB_NAME'] ?? null;
    }

    /**
     * Récupérer les informations d'un compteur depuis l'application Maxit externe
     */
    public function getCompteurFromMaxit(string $numeroCompteur): ?array
    {
        try {
            // Essayer d'abord par API si configurée
            if (!empty($this->maxitApiUrl)) {
                return $this->getCompteurFromApi($numeroCompteur);
            }
            
            // Sinon essayer par base de données directe si configurée
            if ($this->maxitDbHost && $this->maxitDbUser && $this->maxitDbName) {
                return $this->getCompteurFromDatabase($numeroCompteur);
            }
            
            error_log("Aucune configuration Maxit trouvée (ni API ni DB)");
            return null;
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération du compteur depuis Maxit: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer un compteur depuis l'API Maxit
     */
    private function getCompteurFromApi(string $numeroCompteur): ?array
    {
        $url = rtrim($this->maxitApiUrl, '/') . '/api/compteurs/' . urlencode($numeroCompteur);
        
        $response = $this->makeHttpRequest('GET', $url);
        
        if ($response && isset($response['data'])) {
            return $this->normalizeCompteurData($response['data']);
        }
        
        return null;
    }

    /**
     * Récupérer un compteur depuis la base de données Maxit externe
     */
    private function getCompteurFromDatabase(string $numeroCompteur): ?array
    {
        try {
            $pdo = $this->getMaxitDatabaseConnection();
            
            // Requête pour récupérer le compteur et ses informations client
            // Adapter selon la structure de la base Maxit
            $sql = "
                SELECT 
                    c.numero_compteur as numero,
                    c.actif,
                    c.date_creation,
                    cl.nom as client_nom,
                    cl.prenom as client_prenom,
                    cl.telephone as client_telephone,
                    cl.adresse as client_adresse,
                    cl.id as client_id
                FROM compteurs c 
                LEFT JOIN clients cl ON c.client_id = cl.id 
                WHERE c.numero_compteur = :numero
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':numero', $numeroCompteur);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result) {
                return $this->normalizeCompteurData($result);
            }
            
            return null;
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération depuis la DB Maxit: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir une connexion à la base de données Maxit externe
     */
    private function getMaxitDatabaseConnection(): \PDO
    {
        $dsn = "pgsql:host={$this->maxitDbHost};dbname={$this->maxitDbName}";
        
        return new \PDO($dsn, $this->maxitDbUser, $this->maxitDbPassword, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_TIMEOUT => 10
        ]);
    }

    /**
     * Rechercher des compteurs par critères dans l'API Maxit
     */
    public function searchCompteursFromMaxit(array $criteres): array
    {
        try {
            $url = rtrim($this->maxitApiUrl, '/') . '/api/compteurs/search';
            
            $response = $this->makeHttpRequest('POST', $url, $criteres);
            
            if ($response && isset($response['data']) && is_array($response['data'])) {
                return array_map([$this, 'normalizeCompteurData'], $response['data']);
            }
            
            return [];
        } catch (\Exception $e) {
            error_log("Erreur lors de la recherche de compteurs depuis Maxit: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Synchroniser un compteur depuis Maxit vers la base locale
     */
    public function syncCompteurFromMaxit(string $numeroCompteur): ?array
    {
        try {
            $compteurData = $this->getCompteurFromMaxit($numeroCompteur);
            
            if (!$compteurData) {
                return null;
            }

            // Optionnel: Sauvegarder dans la base locale si besoin
            // Pour l'instant, on retourne juste les données récupérées
            // $this->saveCompteurToLocal($compteurData);
            
            return $compteurData;
        } catch (\Exception $e) {
            error_log("Erreur lors de la synchronisation du compteur depuis Maxit: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Effectuer une requête HTTP vers l'API Maxit
     */
    private function makeHttpRequest(string $method, string $url, array $data = null): ?array
    {
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: AppWoyofal/1.0'
        ];
        
        // Ajouter l'authentification si une clé API est configurée
        if (!empty($this->maxitApiKey)) {
            $headers[] = 'Authorization: Bearer ' . $this->maxitApiKey;
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false, // À modifier en production
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ]);
        
        if ($method === 'POST' && $data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new \Exception("Erreur cURL: " . $error);
        }
        
        if ($httpCode >= 400) {
            throw new \Exception("Erreur HTTP {$httpCode}: " . $response);
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }
        
        return $decodedResponse;
    }

    /**
     * Normaliser les données du compteur reçues de Maxit
     */
    private function normalizeCompteurData(array $data): array
    {
        return [
            'numero' => $data['numero'] ?? $data['number'] ?? '',
            'client_id' => $data['client_id'] ?? null,
            'client_nom' => $data['client_nom'] ?? $data['client_name'] ?? '',
            'client_prenom' => $data['client_prenom'] ?? $data['client_firstname'] ?? '',
            'client_telephone' => $data['client_telephone'] ?? $data['client_phone'] ?? '',
            'client_adresse' => $data['client_adresse'] ?? $data['client_address'] ?? '',
            'actif' => isset($data['actif']) ? (bool)$data['actif'] : 
                      (isset($data['active']) ? (bool)$data['active'] : true),
            'date_creation' => $data['date_creation'] ?? $data['created_at'] ?? date('Y-m-d H:i:s'),
            'source' => 'maxit',
            'synced_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Sauvegarder un compteur récupéré de Maxit dans la base locale
     */
    private function saveCompteurToLocal(array $compteurData): void
    {
        try {
            // D'abord, sauvegarder le client s'il n'existe pas
            if (!empty($compteurData['client_nom'])) {
                $this->saveClientToLocal($compteurData);
            }
            
            // Puis sauvegarder le compteur
            $this->saveCompteurRecord($compteurData);
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la sauvegarde locale du compteur: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sauvegarder un client dans la base locale
     */
    private function saveClientToLocal(array $compteurData): int
    {
        try {
            $clientRepository = new \App\Repository\ClientRepository();
            
            // Vérifier si le client existe déjà par nom/prénom
            $existingClient = $clientRepository->findByNomPrenom(
                $compteurData['client_nom'], 
                $compteurData['client_prenom']
            );
            
            if ($existingClient) {
                return $existingClient->getId();
            }
            
            // Créer un nouveau client
            $client = new \App\Entity\Client();
            $client->setNom($compteurData['client_nom'])
                   ->setPrenom($compteurData['client_prenom'])
                   ->setTelephone($compteurData['client_telephone'])
                   ->setAdresse($compteurData['client_adresse']);
            
            return $clientRepository->save($client);
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la sauvegarde du client: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sauvegarder l'enregistrement du compteur
     */
    private function saveCompteurRecord(array $compteurData): void
    {
        try {
            $compteurRepository = new \App\Repository\CompteurRepository();
            
            // Vérifier si le compteur existe déjà
            $existingCompteur = $compteurRepository->findByNumero($compteurData['numero']);
            
            if ($existingCompteur) {
                // Mettre à jour le compteur existant
                $existingCompteur->setActif($compteurData['actif']);
                $compteurRepository->update($existingCompteur);
            } else {
                // Créer un nouveau compteur
                $compteur = new \App\Entity\Compteur();
                $compteur->setNumero($compteurData['numero'])
                         ->setClientId($compteurData['client_id'])
                         ->setActif($compteurData['actif'])
                         ->setDateCreation(new \DateTime($compteurData['date_creation']));
                
                $compteurRepository->save($compteur);
            }
            
        } catch (\Exception $e) {
            error_log("Erreur lors de la sauvegarde du compteur: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier la connectivité avec l'API Maxit
     */
    public function checkMaxitConnection(): array
    {
        try {
            $url = rtrim($this->maxitApiUrl, '/') . '/api/health';
            $response = $this->makeHttpRequest('GET', $url);
            
            return [
                'connected' => true,
                'response_time' => microtime(true),
                'api_version' => $response['version'] ?? 'unknown'
            ];
            
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
                'response_time' => null
            ];
        }
    }
}
