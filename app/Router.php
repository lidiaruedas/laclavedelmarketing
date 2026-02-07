<?php
/**
 * La Clave del Marketing - Router
 * 
 * Sistema de enrutamiento para manejar peticiones HTTP.
 */

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    /**
     * Añadir ruta GET
     */
    public function get(string $path, array $handler, array $middlewares = []): self
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
        return $this;
    }

    /**
     * Añadir ruta POST
     */
    public function post(string $path, array $handler, array $middlewares = []): self
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
        return $this;
    }

    /**
     * Añadir ruta para cualquier método
     */
    private function addRoute(string $method, string $path, array $handler, array $middlewares): void
    {
        // Convertir path a expresión regular
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Ejecutar el router
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Filtrar solo parámetros con nombre
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Ejecutar middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = $middleware . 'Middleware';
                    $middlewarePath = APP_PATH . '/Middleware/' . $middlewareClass . '.php';

                    if (file_exists($middlewarePath)) {
                        require_once $middlewarePath;
                        $middlewareInstance = new $middlewareClass();
                        if (!$middlewareInstance->handle()) {
                            return;
                        }
                    }
                }

                // Ejecutar controlador
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // Ruta no encontrada
        $this->notFound();
    }

    /**
     * Obtener URI limpia
     */
    private function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    /**
     * Ejecutar el handler del controlador
     */
    private function executeHandler(array $handler, array $params): void
    {
        [$controllerName, $method] = $handler;

        $controllerClass = $controllerName . 'Controller';
        $controllerPath = APP_PATH . '/Controllers/' . $controllerClass . '.php';

        if (!file_exists($controllerPath)) {
            throw new Exception("Controlador no encontrado: {$controllerClass}");
        }

        require_once $controllerPath;
        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new Exception("Método no encontrado: {$controllerClass}::{$method}");
        }

        call_user_func_array([$controller, $method], $params);
    }

    /**
     * Manejar ruta no encontrada (404)
     */
    private function notFound(): void
    {
        http_response_code(404);

        if (file_exists(VIEW_PATH . '/errors/404.php')) {
            view('errors.404', [], 'auth');
        } else {
            echo '<h1>404 - Página no encontrada</h1>';
            echo '<p>La página que buscas no existe.</p>';
            echo '<a href="' . APP_URL . '">Volver al inicio</a>';
        }
    }
}
