<?php

namespace App\Controller;

use App\Service\MaxitService;

class MaxitController
{
    private MaxitService $maxitService;

    public function __construct()
    {
        $this->maxitService = new MaxitService();
    }

    /**
     * Rechercher un compteur spécifiquement dans Maxit
     */
    public function searchCompteur(string $numero): void
    {
        try {
            $compteurData = $this->maxitService->getCompteurFromMaxit($numero);
            
            if ($compteurData) {
                $this->sendResponse([
                    'data' => $compteurData,
                    'statut' => 'success',
                    'code' => 200,
                    'message' => 'Compteur trouvé dans Maxit'
                ]);
            } else {
                $this->sendResponse([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 404,
                    'message' => 'Compteur non trouvé dans Maxit'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Erreur dans searchCompteur: " . $e->getMessage());
            $this->sendErrorResponse('Erreur lors de la recherche dans Maxit: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Synchroniser un compteur depuis Maxit vers la base locale
     */
    public function syncCompteur(string $numero): void
    {
        try {
            $compteurData = $this->maxitService->syncCompteurFromMaxit($numero);
            
            if ($compteurData) {
                $this->sendResponse([
                    'data' => $compteurData,
                    'statut' => 'success',
                    'code' => 200,
                    'message' => 'Compteur synchronisé depuis Maxit'
                ]);
            } else {
                $this->sendResponse([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 404,
                    'message' => 'Compteur non trouvé dans Maxit pour synchronisation'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Erreur dans syncCompteur: " . $e->getMessage());
            $this->sendErrorResponse('Erreur lors de la synchronisation: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Rechercher plusieurs compteurs dans Maxit selon des critères
     */
    public function searchMultiple(): void
    {
        try {
            // Récupérer les critères de recherche depuis la requête
            $input = $this->getRequestData();
            
            $criteres = [
                'numero' => $input['numero'] ?? null,
                'client_nom' => $input['client_nom'] ?? null,
                'client_telephone' => $input['client_telephone'] ?? null,
                'actif' => isset($input['actif']) ? (bool)$input['actif'] : null
            ];
            
            // Filtrer les critères vides
            $criteres = array_filter($criteres, function($value) {
                return $value !== null && $value !== '';
            });
            
            if (empty($criteres)) {
                $this->sendErrorResponse('Au moins un critère de recherche est requis', 400);
                return;
            }
            
            $compteurs = $this->maxitService->searchCompteursFromMaxit($criteres);
            
            $this->sendResponse([
                'data' => $compteurs,
                'statut' => 'success',
                'code' => 200,
                'message' => count($compteurs) . ' compteur(s) trouvé(s) dans Maxit',
                'count' => count($compteurs)
            ]);
            
        } catch (\Exception $e) {
            error_log("Erreur dans searchMultiple: " . $e->getMessage());
            $this->sendErrorResponse('Erreur lors de la recherche multiple: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Vérifier la connectivité avec l'API Maxit
     */
    public function health(): void
    {
        try {
            $status = $this->maxitService->checkMaxitConnection();
            
            $this->sendResponse([
                'data' => $status,
                'statut' => $status['connected'] ? 'success' : 'error',
                'code' => $status['connected'] ? 200 : 503,
                'message' => $status['connected'] ? 'Connexion Maxit OK' : 'Connexion Maxit échouée'
            ]);
            
        } catch (\Exception $e) {
            $this->sendErrorResponse('Erreur lors de la vérification de connexion: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Récupérer les données de la requête (GET, POST, ou JSON)
     */
    private function getRequestData(): array
    {
        // Données GET
        if (!empty($_GET)) {
            return $_GET;
        }
        
        // Données POST
        if (!empty($_POST)) {
            return $_POST;
        }
        
        // Données JSON
        $rawInput = file_get_contents('php://input');
        if (!empty($rawInput)) {
            $jsonInput = json_decode($rawInput, true);
            if ($jsonInput !== null && json_last_error() === JSON_ERROR_NONE) {
                return $jsonInput;
            }
        }
        
        return [];
    }

    /**
     * Envoyer une réponse JSON
     */
    private function sendResponse(array $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($data['code']);
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Envoyer une réponse d'erreur JSON
     */
    private function sendErrorResponse(string $message, int $code = 400): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        
        echo json_encode([
            'data' => null,
            'statut' => 'error',
            'code' => $code,
            'message' => $message
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
