# AppWoyofal Dockerfile pour déploiement Render
FROM php:8.3-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    zip unzip \
    git curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le projet
COPY . ./

# Installation des dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuration Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Configuration Supervisor
COPY supervisord.conf /etc/supervisord.conf

# Variables d'environnement par défaut pour Render
ENV DB_USER=postgres
ENV DB_PASSWORD=
ENV DB_HOST=localhost
ENV DB_PORT=5432
ENV DB_NAME=appwoyofal
ENV APP_URL=https://appwoyofal.onrender.com

# Script de démarrage
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exposition du port
EXPOSE 80

# Commande de démarrage
CMD ["/start.sh"]
