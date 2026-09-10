<?php
/**
 * CAMPUS INTER - Rate Limiter
 * Protection contre le spam et les abus
 */

declare(strict_types=1);

namespace CampusInter\Helpers;

use CampusInter\Config\Database;

class RateLimiter
{
    private static int $maxAttempts = 5;
    private static int $windowSeconds = 300; // 5 minutes

    public static function check(string $endpoint, int $maxAttempts = 5, int $windowSeconds = 300): bool
    {
        self::$maxAttempts = $maxAttempts;
        self::$windowSeconds = $windowSeconds;

        $ip = self::getClientIp();
        $db = Database::getInstance()->getConnection();

        // Nettoyer les anciennes entrées
        $stmt = $db->prepare("DELETE FROM rate_limits WHERE first_attempt_at < DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$windowSeconds]);

        // Vérifier les tentatives
        $stmt = $db->prepare("
            SELECT attempts, first_attempt_at 
            FROM rate_limits 
            WHERE ip_address = ? AND endpoint = ?
        ");
        $stmt->execute([$ip, $endpoint]);
        $record = $stmt->fetch();

        if ($record) {
            $elapsed = time() - strtotime($record['first_attempt_at']);

            if ($elapsed < $windowSeconds && $record['attempts'] >= $maxAttempts) {
                return false;
            }

            if ($elapsed >= $windowSeconds) {
                // Réinitialiser le compteur
                $stmt = $db->prepare("
                    UPDATE rate_limits 
                    SET attempts = 1, first_attempt_at = NOW(), last_attempt_at = NOW() 
                    WHERE ip_address = ? AND endpoint = ?
                ");
                $stmt->execute([$ip, $endpoint]);
            } else {
                // Incrémenter
                $stmt = $db->prepare("
                    UPDATE rate_limits 
                    SET attempts = attempts + 1, last_attempt_at = NOW() 
                    WHERE ip_address = ? AND endpoint = ?
                ");
                $stmt->execute([$ip, $endpoint]);
            }
        } else {
            // Nouvelle entrée
            $stmt = $db->prepare("
                INSERT INTO rate_limits (ip_address, endpoint, attempts, first_attempt_at, last_attempt_at) 
                VALUES (?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$ip, $endpoint]);
        }

        return true;
    }

    public static function getRemainingTime(string $endpoint): int
    {
        $ip = self::getClientIp();
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT first_attempt_at 
            FROM rate_limits 
            WHERE ip_address = ? AND endpoint = ? AND attempts >= ?
        ");
        $stmt->execute([$ip, $endpoint, self::$maxAttempts]);
        $record = $stmt->fetch();

        if (!$record) {
            return 0;
        }

        $elapsed = time() - strtotime($record['first_attempt_at']);
        $remaining = self::$windowSeconds - $elapsed;

        return max(0, $remaining);
    }

    private static function getClientIp(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',  // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = explode(',', $_SERVER[$header])[0];
                return trim($ip);
            }
        }

        return '0.0.0.0';
    }
}
