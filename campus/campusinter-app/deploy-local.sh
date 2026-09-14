#!/bin/bash

# Campus Inter - Script de déploiement local
# Ce script construit et lance l'application en local

set -e

echo "🚀 Campus Inter - Déploiement local"
echo "=================================="

# Vérifier que Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker."
    exit 1
fi

# Construire l'image
echo "📦 Construction de l'image Docker..."
docker build -t campus-inter .

# Arrêter les anciens conteneurs
echo "🛑 Arrêt des anciens conteneurs..."
docker stop campus-inter-app 2>/dev/null || true
docker rm campus-inter-app 2>/dev/null || true

# Lancer le conteneur
echo "▶️  Démarrage de l'application..."
docker run -d \
    --name campus-inter-app \
    -p 8080:80 \
    -e APP_ENV=development \
    -e APP_DEBUG=true \
    campus-inter

echo ""
echo "✅ Application démarrée avec succès!"
echo ""
echo "📍 Accès:"
echo "   - Site: http://localhost:8080"
echo "   - Admin: http://localhost:8080/login"
echo "   - Identifiants: admin@campusinter.com / Admin@123"
echo ""
echo "📋 Commandes utiles:"
echo "   - Voir les logs: docker logs -f campus-inter-app"
echo "   - Arrêter: docker stop campus-inter-app"
echo "   - Redémarrer: docker restart campus-inter-app"
echo ""
