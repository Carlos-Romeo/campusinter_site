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

# Exécuter les migrations additionnelles
echo "Exécution des migrations..."
for migration in /var/www/database/migrations/*.sql; do
    if [ -f "$migration" ]; then
        MIGRATION_NAME=$(basename "$migration")
        # Vérifier si la migration a déjà été exécutée
        EXISTS=$(mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='campusinter' AND table_name='schema_migrations'" 2>/dev/null)
        
        if [ "$EXISTS" -eq 0 ]; then
            # Créer la table des migrations si elle n'existe pas
            mysql -u root campusinter <<EOF
CREATE TABLE IF NOT EXISTS schema_migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration_name VARCHAR(255) NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_migration_name (migration_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOF
        fi
        
        # Vérifier si la migration a déjà été exécutée
        ALREADY_RUN=$(mysql -u root -N -e "SELECT COUNT(*) FROM campusinter.schema_migrations WHERE migration_name='$MIGRATION_NAME'" 2>/dev/null)
        
        if [ "$ALREADY_RUN" -eq 0 ]; then
            echo "Exécution de la migration: $MIGRATION_NAME"
            mysql -u root campusinter < "$migration"
            mysql -u root campusinter -e "INSERT INTO schema_migrations (migration_name) VALUES ('$MIGRATION_NAME')"
            echo "Migration $MIGRATION_NAME exécutée avec succès."
        else
            echo "Migration $MIGRATION_NAME déjà exécutée, passage..."
        fi
    fi
done

# Créer le dossier storage/uploads s'il n'existe pas
mkdir -p /var/www/storage/uploads
chmod 755 /var/www/storage/uploads

# Démarrer Apache
echo "Démarrage d'Apache..."
apache2-foreground
