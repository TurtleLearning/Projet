<?php

namespace App;

class Router {

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
        if (file_exists($filePath)) {
            require $filePath;
            return;
        }

        // Si rien n'est trouvé, afficher la page 404
        require BASE_PATH . '/includes/layout/404.php';
    }
}

?>