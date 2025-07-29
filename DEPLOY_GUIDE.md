# 🚀 Guide de Déploiement AppWoyofal sur Render

## 📋 Fichiers de Déploiement Créés

✅ **Dockerfile** - Image Docker optimisée pour Render  
✅ **render.yaml** - Configuration Render automatique  
✅ **start.sh** - Script de démarrage avec migrations automatiques  
✅ **nginx.conf** - Configuration serveur web optimisée  
✅ **supervisord.conf** - Gestionnaire de processus  
✅ **.dockerignore** - Optimisation de build  

## 🎯 Déploiement sur Render

### Option 1: Déploiement Automatique (Recommandé)

1. **Push vers GitHub**
```bash
git add .
git commit -m "AppWoyofal ready for deployment"
git push origin main
```

2. **Sur Render.com**
   - Connecter le repository GitHub
   - Render détectera automatiquement `render.yaml`
   - Les services seront créés automatiquement

### Option 2: Déploiement Manuel

1. **Créer Web Service**
   - Runtime: Docker
   - Build Command: (vide)
   - Start Command: (vide, utilise CMD du Dockerfile)

2. **Variables d'environnement** (automatiques via render.yaml)
   - `DB_USER` → Connecté à PostgreSQL
   - `DB_PASSWORD` → Connecté à PostgreSQL  
   - `DB_HOST` → Connecté à PostgreSQL
   - `DB_NAME` → appwoyofal
   - `APP_URL` → https://appwoyofal.onrender.com

## 🗄️ Base de Données

- **PostgreSQL gratuit** créé automatiquement
- **Migrations automatiques** au démarrage
- **Seeders automatiques** si DB vide
- **Tables créées** : clients, compteurs, tranches, achats, logs_achats

## 🔗 Endpoints API Disponibles

Une fois déployé sur `https://appwoyofal.onrender.com`:

### 🔍 Vérification Compteur
```http
GET /api/woyofal/compteur/CPT123456
```

### 💳 Achat Crédit
```http
POST /api/woyofal/achat
Content-Type: application/json

{
  "numero_compteur": "CPT123456",
  "montant": 25000
}
```

### 📊 Statistiques
```http
GET /api/woyofal/stats
```

### 🏥 Health Check
```http
GET /health
```

## 🧪 Données de Test Incluses

**Clients Test :**
- Die NIANG (+221771234567) → Compteur CPT123456
- Fatou DIOP (+221772345678) → Compteur CPT789012
- Moussa FALL (+221773456789) → Compteur CPT345678

**Tranches Tarifaires :**
- Tranche 1: 0-5000 FCFA → 98 FCFA/kWh
- Tranche 2: 5001-15000 FCFA → 105 FCFA/kWh
- Tranche 3: 15001-30000 FCFA → 115 FCFA/kWh
- Tranche 4: 30001+ FCFA → 125 FCFA/kWh

## 🔧 Fonctionnalités Automatiques

✅ **Auto-migrations** au démarrage  
✅ **Auto-seeding** si DB vide  
✅ **Health checks** intégrés  
✅ **CORS** configuré pour MaxITSA  
✅ **Logs** automatiques des transactions  
✅ **SSL/HTTPS** automatique sur Render  

## 🎉 Utilisation avec MaxITSA

Une fois déployé, tu peux utiliser l'API dans MaxITSA :

```php
// Vérifier compteur
$response = file_get_contents('https://appwoyofal.onrender.com/api/woyofal/compteur/' . $numeroCompteur);

// Acheter crédit
$data = json_encode([
    'numero_compteur' => $numeroCompteur,
    'montant' => $montant
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $data
    ]
]);

$response = file_get_contents('https://appwoyofal.onrender.com/api/woyofal/achat', false, $context);
```

## 🛠️ Résolution de Problèmes

**Build Failed ?**
- Vérifier que tous les fichiers sont dans `/set/`
- Vérifier le Dockerfile path

**DB Connection Error ?**
- Render connecte automatiquement les variables DB
- Attendre que PostgreSQL soit prêt

**API 404 ?**
- Vérifier que nginx pointe vers `/public/`
- Vérifier les routes dans `routes/route.web.php`

**Ready for Production! 🚀**
