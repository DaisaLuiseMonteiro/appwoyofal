#!/bin/bash

# Exit on any error
set -e

echo "Starting AppWoyofal container..."

# Run database migrations if needed
if [ -f "/var/www/html/migrations/migration.php" ]; then
    echo "Running database migrations..."
    cd /var/www/html
    php migrations/migration.php || echo "Migration failed, continuing..."
fi

# Start services with supervisor
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
