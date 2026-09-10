<?php
/**
 * CAMPUS INTER - Controller Base
 * Classe parente pour tous les controllers
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Config\Database;

abstract class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Afficher une vue
     */
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);
        $viewFile = dirname(__DIR__) . '/Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vue non trouvée: {$viewPath}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Charger le layout correspondant
        if (strpos($viewPath, 'admin/') === 0) {
            require dirname(__DIR__) . '/Views/admin/layout.php';
        } else {
            require dirname(__DIR__) . '/Views/public/layout.php';
        }
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Rediriger
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    /**
     * Retourner une erreur 404
     */
    protected function notFound(): void
    {
        http_response_code(404);
        require dirname(__DIR__) . '/Views/errors/404.php';
        exit;
    }

    /**
     * Retourner une erreur 403
     */
    protected function forbidden(): void
    {
        http_response_code(403);
        require dirname(__DIR__) . '/Views/errors/403.php';
        exit;
    }

    /**
     * Vérifier si la requête est AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtenir le contenu JSON de la requête
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        return is_array($data) ? $data : [];
    }
}
