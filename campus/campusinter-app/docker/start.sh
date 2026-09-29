#!/bin/bash

# Campus Inter - Script de démarrage
# Supporte MySQL (local) et PostgreSQL (Render)

echo "Démarrage de Campus Inter..."

# Créer le dossier storage/uploads s'il n'existe pas
mkdir -p /var/www/storage/uploads
chmod 755 /var/www/storage/uploads

# Vérifier si on utilise PostgreSQL (DATABASE_URL défini)
if [ -n "$DATABASE_URL" ]; then
    echo "Mode PostgreSQL détecté (DATABASE_URL défini)"
    export DB_DRIVER=pgsql
    
    # Extraire les infos de DATABASE_URL
    # Format: postgresql://USER:PASSWORD@HOST:PORT/DATABASE
    DB_HOST=$(echo $DATABASE_URL | sed -n 's|.*@\([^:]*\):\([0-9]*\)/.*|\1|p')
    DB_PORT=$(echo $DATABASE_URL | sed -n 's|.*@\([^:]*\):\([0-9]*\)/.*|\2|p')
    DB_NAME=$(echo $DATABASE_URL | sed -n 's|.*/\([^?]*\).*|\1|p')
    DB_USER=$(echo $DATABASE_URL | sed -n 's|.*://\([^:]*\):.*|\1|p')
    DB_PASSWORD=$(echo $DATABASE_URL | sed -n 's|.*://[^:]*:\([^@]*\)@.*|\1|p')
    
    export DB_HOST DB_PORT DB_NAME DB_USER DB_PASSWORD
    
    echo "Connexion à PostgreSQL: $DB_HOST:$DB_PORT/$DB_NAME"
    
    # Vérifier si la base de données est vide (pas de tables)
    TABLE_COUNT=$(PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -t -c "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'public'" 2>/dev/null | tr -d ' ')
    
    if [ "$TABLE_COUNT" -eq 0 ] || [ -z "$TABLE_COUNT" ]; then
        echo "Base de données vide détectée. Importation du schéma..."
        
        if [ -f /var/www/database/campusinter_pg.sql ]; then
            PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /var/www/database/campusinter_pg.sql
            echo "Schéma PostgreSQL importé avec succès."
        else
            echo "ERREUR: Fichier schema non trouvé: /var/www/database/campusinter_pg.sql"
        fi
    else
        echo "Base de données existante détectée ($TABLE_COUNT tables). Import ignoré."
    fi
    
    # Exécuter les migrations additionnelles si le dossier existe
    if [ -d /var/www/database/migrations ]; then
        echo "Vérification des migrations PostgreSQL..."
        for migration in /var/www/database/migrations/*_pg.sql; do
            if [ -f "$migration" ]; then
                MIGRATION_NAME=$(basename "$migration")
                
                # Vérifier si la migration a déjà été exécutée
                ALREADY_RUN=$(PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -t -c "SELECT COUNT(*) FROM schema_migrations WHERE migration_name = '$MIGRATION_NAME'" 2>/dev/null | tr -d ' ')
                
                if [ "$ALREADY_RUN" = "0" ] || [ -z "$ALREADY_RUN" ]; then
                    echo "Exécution de la migration: $MIGRATION_NAME"
                    PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f "$migration"
                    PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -c "INSERT INTO schema_migrations (migration_name) VALUES ('$MIGRATION_NAME')" 2>/dev/null
                    echo "Migration $MIGRATION_NAME exécutée avec succès."
                else
                    echo "Migration $MIGRATION_NAME déjà exécutée, passage..."
                fi
            fi
        done
    fi
    
else
    echo "Mode MySQL local détecté"
    export DB_DRIVER=mysql
    
    # Démarrer MySQL si disponible
    if command -v service &> /dev/null; then
        service mariadb start 2>/dev/null || true
        
        # Attendre que MySQL soit prêt
        until mysqladmin ping -h localhost --silent 2>/dev/null; do
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
        TABLE_COUNT=$(mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='campusinter'" 2>/dev/null || echo "0")
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
                EXISTS=$(mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='campusinter' AND table_name='schema_migrations'" 2>/dev/null || echo "0")
                
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
                ALREADY_RUN=$(mysql -u root -N -e "SELECT COUNT(*) FROM campusinter.schema_migrations WHERE migration_name='$MIGRATION_NAME'" 2>/dev/null || echo "0")
                
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
    fi
fi

# Démarrer Apache
echo "Démarrage d'Apache..."
apache2-foreground
