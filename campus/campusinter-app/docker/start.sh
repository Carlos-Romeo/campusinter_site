#!/bin/bash

# Démarrer MySQL
service mariadb start

# Attendre que MySQL soit prêt
until mysqladmin ping -h localhost --silent; do
    echo "Attente de MySQL..."
    sleep 2
done

# Créer la base de données si elle n'existe pas
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS campusinter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'campususer'@'localhost' IDENTIFIED BY 'campus123';
GRANT ALL PRIVILEGES ON campusinter.* TO 'campususer'@'localhost';
FLUSH PRIVILEGES;
EOF

# Importer la base de données si elle est vide
TABLE_COUNT=$(mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='campusinter'")
if [ "$TABLE_COUNT" -eq 0 ]; then
    echo "Importation de la base de données..."
    mysql -u root campusinter < /var/www/database/campusinter.sql
    echo "Base de données importée avec succès."
fi

# Démarrer Apache
echo "Démarrage d'Apache..."
apache2-foreground
