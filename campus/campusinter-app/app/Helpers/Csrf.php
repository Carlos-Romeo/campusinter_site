<?php
/**
 * CAMPUS INTER - Helper CSRF
 * Protection contre les attaques CSRF
 */

declare(strict_types=1);

namespace CampusInter\Helpers;

class Csrf
{
    public static function generate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();

        return $token;
    }

    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $token = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $token ?? ($_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }

        // Vérifier l'âge du token (max 2 heures)
        if (time() - ($_SESSION['csrf_token_time'] ?? 0) > 7200) {
            self::invalidate();
            return false;
        }

        $valid = hash_equals($_SESSION['csrf_token'], $token);

        if ($valid) {
            self::invalidate();
        }

        return $valid;
    }

    public static function invalidate(): void
    {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }
}
