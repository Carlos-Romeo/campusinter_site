<?php
/**
 * CAMPUS INTER - Logger
 * Journalisation des événements et erreurs
 */

declare(strict_types=1);

namespace CampusInter\Helpers;

use CampusInter\Config\Database;

class Logger
{
    private static string $logDir;

    public static function init(): void
    {
        self::$logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    public static function info(string $message, string $category = 'system', ?array $context = null): void
    {
        self::log('info', $message, $category, $context);
    }

    public static function warning(string $message, string $category = 'system', ?array $context = null): void
    {
        self::log('warning', $message, $category, $context);
    }

    public static function error(string $message, string $category = 'system', ?array $context = null): void
    {
        self::log('error', $message, $category, $context);
    }

    public static function critical(string $message, string $category = 'system', ?array $context = null): void
    {
        self::log('critical', $message, $category, $context);
    }

    private static function log(string $level, string $message, string $category, ?array $context): void
    {
        self::init();

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // Log dans le fichier
        $logLine = sprintf(
            "[%s] %s [%s] %s | IP: %s\n",
            $timestamp,
            strtoupper($level),
            $category,
            $message,
            $ip
        );

        if ($context) {
            $logLine .= "Context: " . json_encode($context, JSON_UNESCAPED_UNICODE) . "\n";
        }

        $logFile = self::$logDir . '/' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

        // Log dans la base de données
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                INSERT INTO logs (level, category, message, context, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $level,
                $category,
                $message,
                $context ? json_encode($context, JSON_UNESCAPED_UNICODE) : null,
                $ip,
                mb_substr($userAgent, 0, 255),
            ]);
        } catch (\Exception $e) {
            // Si la DB ne fonctionne pas, on log juste le fichier
            error_log('Logger DB error: ' . $e->getMessage());
        }
    }
}
