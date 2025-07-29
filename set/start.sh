#!/bin/bash

# Script de démarrage pour AppWoyofal sur Render
echo "🚀 Démarrage d'AppWoyofal..."

# Aller dans le dossier de l'application
cd /var/www/html/archiectureprojet

# Créer le fichier .env à partir des variables d'environnement
echo "📝 Configuration de l'environnement..."
cat > .env << EOF
DB_USER=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_NAME=${DB_NAME}
APP_URL=${APP_URL}
EOF

# Attendre que la base de données soit disponible
echo "⏳ Attente de la base de données..."
for i in {1..30}; do
    if pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USER}" -d "${DB_NAME}" 2>/dev/null; then
        echo "✅ Base de données disponible"
        break
    fi
    echo "⏳ Tentative $i/30..."
    sleep 2
done

# Exécuter les migrations
echo "🛠️ Exécution des migrations..."
if php migrations/migration.php; then
    echo "✅ Migrations exécutées avec succès"
else
    echo "❌ Erreur lors des migrations"
fi

# Exécuter les seeders (optionnel, seulement si pas de données)
echo "🌱 Vérification des données initiales..."
CLIENTS_COUNT=$(php -r "
require_once 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
\$dsn = \"pgsql:host={\$_ENV['DB_HOST']};port={\$_ENV['DB_PORT']};dbname={\$_ENV['DB_NAME']}\";
try {
    \$pdo = new PDO(\$dsn, \$_ENV['DB_USER'], \$_ENV['DB_PASSWORD']);
    \$stmt = \$pdo->query('SELECT COUNT(*) FROM clients');
    echo \$stmt->fetchColumn();
} catch (Exception \$e) {
    echo '0';
}
")

if [ "$CLIENTS_COUNT" -eq "0" ]; then
    echo "🌱 Insertion des données initiales..."
    if php -r "
    require_once 'vendor/autoload.php';
    \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    \$dotenv->load();
    \$dsn = \"pgsql:host={\$_ENV['DB_HOST']};port={\$_ENV['DB_PORT']};dbname={\$_ENV['DB_NAME']}\";
    \$pdo = new PDO(\$dsn, \$_ENV['DB_USER'], \$_ENV['DB_PASSWORD']);
    \$sql = file_get_contents('seeders/script.sql');
    \$pdo->exec(\$sql);
    echo 'Seeders exécutés avec succès';
    "; then
        echo "✅ Données initiales insérées"
    else
        echo "⚠️ Erreur lors de l'insertion des données initiales (peut-être déjà présentes)"
    fi
else
    echo "✅ Données déjà présentes ($CLIENTS_COUNT clients)"
fi

# Vérifier les permissions
echo "🔧 Configuration des permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Démarrer Supervisor
echo "🎯 Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
