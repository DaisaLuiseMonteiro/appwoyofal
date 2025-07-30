# Intégration avec l'application Maxit

Cette documentation explique comment configurer l'intégration entre AppWoyofal et l'application externe Maxit pour récupérer les données de compteurs.

## Configuration

### Variables d'environnement

Ajoutez ces variables à votre fichier `.env` :

```env
# Option 1: Intégration via API REST de Maxit
MAXIT_API_URL=https://api.votre-app-maxit.com
MAXIT_API_KEY=votre_cle_api_maxit
MAXIT_TIMEOUT=30

# Option 2: Intégration via connexion directe à la BD Maxit
MAXIT_DB_HOST=host-bd-maxit.com
MAXIT_DB_USER=utilisateur_maxit
MAXIT_DB_PASSWORD=mot_de_passe_maxit
MAXIT_DB_NAME=nom_base_maxit
MAXIT_DB_PORT=5432
```

## Fonctionnement

### 1. Recherche automatique avec fallback

Quand vous utilisez l'API standard `/api/woyofal/compteur/{numero}`, le système :

1. **Cherche d'abord dans la base locale**
2. **Si non trouvé, cherche automatiquement dans Maxit** (API ou BD)
3. **Retourne les données avec l'indication de la source**

```bash
# Exemple d'appel
GET /api/woyofal/compteur/CPT123456

# Réponse avec source locale
{
    "data": {
        "compteur": "CPT123456",
        "client": "Nom Client",
        "actif": true,
        "date_creation": "2025-01-01T00:00:00Z",
        "source": "local"
    },
    "statut": "success",
    "message": "Compteur trouvé dans la base locale"
}

# Réponse avec source Maxit
{
    "data": {
        "compteur": "CPT123456",
        "client": "Nom Client",
        "actif": true,
        "date_creation": "2025-01-01T00:00:00Z",
        "source": "maxit",
        "synced_at": "2025-01-01T12:00:00Z"
    },
    "statut": "success",
    "message": "Compteur récupéré depuis Maxit et synchronisé"
}
```

### 2. API dédiées Maxit

Nouvelles routes pour contrôler spécifiquement l'intégration Maxit :

#### Vérifier la connectivité
```bash
GET /api/maxit/health
```

#### Rechercher directement dans Maxit
```bash
GET /api/maxit/compteur/{numero}
```

#### Synchroniser un compteur depuis Maxit
```bash
POST /api/maxit/sync/{numero}
```

#### Recherche multiple dans Maxit
```bash
POST /api/maxit/search
Content-Type: application/json

{
    "numero": "CPT123456",           // optionnel
    "client_nom": "Nom",             // optionnel
    "client_telephone": "777123456", // optionnel
    "actif": true                    // optionnel
}
```

## Structure de données attendue de Maxit

### Via API REST

L'API Maxit doit retourner des données au format :

```json
{
    "data": {
        "numero": "CPT123456",
        "client_nom": "Nom",
        "client_prenom": "Prénom", 
        "client_telephone": "777123456",
        "client_adresse": "Adresse complète",
        "actif": true,
        "date_creation": "2025-01-01T00:00:00Z"
    }
}
```

### Via Base de données

Le service s'attend à trouver ces tables dans la BD Maxit :

```sql
-- Table compteurs
CREATE TABLE compteurs (
    numero_compteur VARCHAR(50),
    actif BOOLEAN,
    date_creation TIMESTAMP,
    client_id INTEGER
);

-- Table clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(20),
    adresse TEXT
);
```

## Exemples d'utilisation

### Test avec un compteur existant localement
```bash
curl http://localhost:8084/api/woyofal/compteur/CPT123456
```

### Test de connectivité Maxit
```bash
curl http://localhost:8084/api/maxit/health
```

### Recherche directe dans Maxit
```bash
curl http://localhost:8084/api/maxit/compteur/CPT999999
```

### Recherche multiple dans Maxit
```bash
curl -X POST http://localhost:8084/api/maxit/search \
  -H "Content-Type: application/json" \
  -d '{"client_nom": "Diop"}'
```

## Avantages de cette intégration

1. **Transparence** : L'API principale fonctionne normalement, avec fallback automatique
2. **Flexibilité** : Support API REST ou connexion directe BD
3. **Contrôle** : APIs dédiées pour gérer spécifiquement l'intégration
4. **Performance** : Cache local avec synchronisation à la demande
5. **Fiabilité** : Gestion d'erreurs et logs détaillés

## Configuration recommandée

Pour une utilisation en production, configurez :

1. **L'API REST** si Maxit expose une API (plus sécurisé)
2. **La connexion BD directe** en fallback ou si pas d'API disponible
3. **Les timeouts appropriés** selon votre réseau
4. **Les logs** pour surveiller les intégrations

Cette intégration permet à AppWoyofal de récupérer automatiquement les données de compteurs depuis votre application Maxit externe sans modification des utilisateurs finaux.
