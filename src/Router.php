<?php

namespace App;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[$path] = ['method' => $method, 'handler' => $handler];
    }

    public function dispatch($request) {
        // Nettoyer la requête
        $request = trim($request, '/');

        // Gérer la page d'accueil
        if ($request === '' || $request === 'index.php') {
            try {
                $controller = new \App\Controllers\HomeController();
                return $controller->index();
            } catch (\Exception $e) {
                error_log("Erreur dans le routeur : " . $e->getMessage());
                require BASE_PATH . '/includes/layout/404.php';
            }
        }

        // Vérifier si la route existe dans notre tableau de routes
        if (isset($this->routes[$request])) {
            $route = $this->routes[$request];
            if ($_SERVER['REQUEST_METHOD'] === $route['method']) {
                return $this->executeHandler($route['handler']);
            }
        }

        // Si aucune route trouvée, chercher le fichier PHP correspondant
        $filePath = BASE_PATH . '/public/' . $request . '.php';
        // Vérification de sécurité pour le fichier
        if (file_exists($filePath) && strpos($filePath, BASE_PATH . '/public/') === 0) {
            require $filePath;
            return;
        }

        // Si rien n'est trouvé, afficher la page 404
        require BASE_PATH . '/includes/layout/404.php';
    }
}

?>