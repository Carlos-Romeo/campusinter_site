-- =====================================================
-- CAMPUS INTER - Migration complète
-- Ajout des fonctionnalités paiement et documents
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Vérifier et ajouter le statut 'paid' à la table applications
-- -----------------------------------------------------
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'applications'
    AND COLUMN_NAME = 'status'
);

-- Si la colonne existe, la modifier pour ajouter 'paid'
SET @alter_sql = (
    SELECT CONCAT(
        'ALTER TABLE `applications` MODIFY COLUMN `status` ',
        'ENUM(''new'', ''processing'', ''accepted'', ''rejected'', ''archived'', ''paid'') ',
        'NOT NULL DEFAULT ''new'''
    )
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'applications'
    AND COLUMN_NAME = 'status'
    AND COLUMN_TYPE NOT LIKE '%paid%'
    LIMIT 1
);

-- Exécuter l'alter si nécessaire
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- -----------------------------------------------------
-- Table: application_documents
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `application_documents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id` INT UNSIGNED NOT NULL,
  `document_type` VARCHAR(50) NOT NULL COMMENT 'diploma, bac, cv, other',
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_doc_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_doc_application` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: schema_migrations (pour追踪 les migrations)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration_name` VARCHAR(255) NOT NULL,
  `executed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_migration_name` (`migration_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
