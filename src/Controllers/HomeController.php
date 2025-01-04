<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $pageTitle = "Accueil - Le Petit Chalet dans La Montagne";
        
        require BASE_PATH . '/views/home/index.php';
    }
}
