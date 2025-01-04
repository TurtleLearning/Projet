<?php
namespace App\Controllers;

class ActivitesController {
    public function index() {

        $pageTitle = "Activités - Le Petit Chalet dans La Montagne";

        require BASE_PATH . '/views/activites/index.php';
    }
}