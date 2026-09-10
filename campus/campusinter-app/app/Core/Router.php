<?php
/**
 * CAMPUS INTER - Routeur simple
 * Gestion des routes publiques et admin
 */

declare(strict_types=1);

namespace CampusInter\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, callable $handler, array $middlewares = []): self
    {
        $this->routes['GET'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return $this;
    }

    public function post(string $path, callable $handler, array $middlewares = []): self
    {
        $this->routes['POST'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
        return $this;
    }

    public function middleware(string $name, callable $handler): self
    {
        $this->middlewares[$name] = $handler;
        return $this;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Supprimer le slash final
        $uri = rtrim($uri, '/') ?: '/';

        // Trouver la route correspondante
        $route = $this->matchRoute($method, $uri);

        if ($route === null) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            return;
        }

        // Exécuter les middlewares
        foreach ($route['middlewares'] as $middlewareName) {
            if (isset($this->middlewares[$middlewareName])) {
                ($this->middlewares[$middlewareName])();
            }
        }

        // Exécuter le handler
        ($route['handler'])();
    }

    private function matchRoute(string $method, string $uri): ?array
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        // Correspondance exacte
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        // Correspondance avec paramètres (ex: /admin/applications/{id})
        foreach ($this->routes[$method] as $pattern => $route) {
            $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                // Stocker les paramètres dans $_GET
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $_GET[$key] = $value;
                    }
                }
                return $route;
            }
        }

        return null;
    }
}
