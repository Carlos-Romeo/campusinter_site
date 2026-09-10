<?php
/**
 * CAMPUS INTER - Auth Controller
 * Gestion de l'authentification admin
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Helpers\Csrf;
use CampusInter\Helpers\Logger;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de login
     */
    public function showLogin(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/admin');
            return;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require dirname(__DIR__) . '/Views/auth/login.php';
    }

    /**
     * Traitement du login
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Vérifier CSRF
        if (!Csrf::verify()) {
            $_SESSION['login_error'] = 'Token de sécurité invalide.';
            $this->redirect('/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation basique
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Veuillez remplir tous les champs.';
            $this->redirect('/login');
            return;
        }

        // Rechercher l'utilisateur
        $db = $this->db->getConnection();
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            Logger::warning("Tentative de login échouée pour: {$email}", 'security');
            $_SESSION['login_error'] = 'Email ou mot de passe incorrect.';
            $this->redirect('/login');
            return;
        }

        // Connecter l'utilisateur
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_login_time'] = time();

        // Mettre à jour la dernière connexion
        $stmt = $db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);

        Logger::info("Admin connecté: {$email}", 'security');

        $this->redirect('/admin');
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        $email = $_SESSION['admin_email'] ?? 'unknown';
        Logger::info("Admin déconnecté: {$email}", 'security');

        session_destroy();
        $this->redirect('/login');
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
    }

    /**
     * Vérifier l'expiration de session
     */
    public static function isSessionExpired(): bool
    {
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        $lifetime = $config['SESSION_LIFETIME'] ?? 7200;

        return (time() - ($_SESSION['admin_login_time'] ?? 0)) > $lifetime;
    }
}
