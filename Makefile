# AppWoyofal - Commandes de développement
.PHONY: help install dev migrate seed test build docker-run deploy

# Variables
PROJECT_DIR = set/archiectureprojet
PHP_SERVER = localhost:8083

help: ## Afficher cette aide
	@echo "AppWoyofal - Commandes disponibles:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

install: ## Installer les dépendances
	cd $(PROJECT_DIR) && composer install

dev: install ## Démarrer le serveur de développement
	@echo "🚀 Démarrage du serveur sur http://$(PHP_SERVER)"
	cd $(PROJECT_DIR)/public && php -S $(PHP_SERVER)

migrate: ## Exécuter les migrations
	cd $(PROJECT_DIR) && php migrations/migration.php

seed: ## Insérer les données de test
	cd $(PROJECT_DIR) && php -r "require 'vendor/autoload.php'; \$$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); \$$dotenv->load(); \$$dsn = \"pgsql:host={\$$_ENV['DB_HOST']};port={\$$_ENV['DB_PORT']};dbname={\$$_ENV['DB_NAME']}\"; \$$pdo = new PDO(\$$dsn, \$$_ENV['DB_USER'], \$$_ENV['DB_PASSWORD']); \$$sql = file_get_contents('seeders/script.sql'); \$$pdo->exec(\$$sql); echo 'Seeders exécutés avec succès\n';"

test: ## Afficher les tests API disponibles
	@echo "📋 Tests API disponibles dans: $(PROJECT_DIR)/tests/woyofal-api.http"
	@echo "🔗 Endpoints principaux:"
	@echo "  POST /api/woyofal/achat"
	@echo "  GET  /api/woyofal/compteur/{numero}"
	@echo "  GET  /api/woyofal/stats"

build: ## Construire l'image Docker
	docker build -t appwoyofal .

docker-run: build ## Lancer le container Docker
	docker run -p 8083:80 appwoyofal

deploy: ## Déployer sur Render
	git add .
	git commit -m "Deploy AppWoyofal to Render" || true
	git push origin main

setup: install migrate seed ## Configuration complète (install + migrate + seed)
	@echo "✅ Configuration terminée !"
	@echo "🚀 Lancer avec: make dev"

status: ## Vérifier le statut du projet
	@echo "📊 Statut AppWoyofal:"
	@echo "  - Projet: $(PROJECT_DIR)"
	@echo "  - Serveur: http://$(PHP_SERVER)" 
	@echo "  - Docker: $(shell docker --version 2>/dev/null || echo 'Non installé')"
	@echo "  - PHP: $(shell php --version | head -n1)"
	@echo "  - Composer: $(shell composer --version | head -n1)"
