<?php
namespace App\src\controller;

class HomeController {
    public function index() {
        $pageTitle = "Accueil - Le Petit Chalet dans La Montagne";
        
        // Charger la vue de la page d'accueil
        require BASE_PATH . '/views/home/index.php';
    }
}
