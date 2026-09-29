<?php
/**
 * CAMPUS INTER - Connexion PDO
 * Singleton pour la gestion de la connexion base de données
 * Supporte MySQL et PostgreSQL
 */

declare(strict_types=1);

namespace CampusInter\Config;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/config.php';

        // Support DATABASE_URL pour Render PostgreSQL
        $databaseUrl = getenv('DATABASE_URL');
        
        if ($databaseUrl) {
            // Parser l'URL PostgreSQL de Render
            // Format: postgresql://USER:PASSWORD@HOST:PORT/DATABASE
            $parsed = parse_url($databaseUrl);
            $config['DB_HOST'] = $parsed['host'] ?? $config['DB_HOST'];
            $config['DB_PORT'] = $parsed['port'] ?? '5432';
            $config['DB_NAME'] = ltrim($parsed['path'] ?? '', '/');
            $config['DB_USER'] = $parsed['user'] ?? $config['DB_USER'];
            $config['DB_PASSWORD'] = $parsed['pass'] ?? $config['DB_PASSWORD'];
        }

        $driver = $config['DB_DRIVER'] ?? 'mysql';

        if ($driver === 'pgsql') {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $config['DB_HOST'],
                $config['DB_PORT'],
                $config['DB_NAME']
            );
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['DB_HOST'],
                $config['DB_PORT'],
                $config['DB_NAME'],
                $config['DB_CHARSET']
            );
        }

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            if ($driver === 'mysql') {
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
            }

            $this->connection = new PDO($dsn, $config['DB_USER'], $config['DB_PASSWORD'], $options);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new \RuntimeException('Erreur de connexion à la base de données.');
        }
    }

    // Empêcher le clonage
    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getDriver(): string
    {
        return $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}
