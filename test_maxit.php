<?php

require_once 'vendor/autoload.php';
require_once 'app/config/env.php';

use App\Service\MaxitService;
use App\Service\WoyofalService;

echo "=== Test d'intégration avec l'application Maxit ===\n\n";

// Test 1: Vérifier la configuration
echo "1. Configuration Maxit:\n";
echo "   - API URL: " . ($_ENV['MAXIT_API_URL'] ?? 'Non configurée') . "\n";
echo "   - DB Host: " . ($_ENV['MAXIT_DB_HOST'] ?? 'Non configurée') . "\n";
echo "\n";

// Test 2: Tester la recherche d'un compteur via WoyofalService
echo "2. Test de recherche de compteur (avec fallback Maxit):\n";
$woyofalService = new WoyofalService();

// Utiliser un numéro de compteur existant dans votre base locale pour le test
$numeroTest = 'CPT123456';
echo "   Recherche du compteur: $numeroTest\n";

$result = $woyofalService->verifierCompteur($numeroTest);
echo "   Résultat: " . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Test 3: Tester directement le service Maxit
echo "3. Test direct du service Maxit:\n";
$maxitService = new MaxitService();

$compteurFromMaxit = $maxitService->getCompteurFromMaxit($numeroTest);
if ($compteurFromMaxit) {
    echo "   Compteur trouvé dans Maxit: " . json_encode($compteurFromMaxit, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "   Compteur non trouvé dans Maxit (normal si pas encore configuré)\n";
}

echo "\n=== Test terminé ===\n";
