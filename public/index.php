<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/src/autoload.php';
require_once BASE_PATH . '/src/Config/constants.php';

// Debug
error_log("BASE_PATH: " . BASE_PATH);
error_log("Tentative de chargement de HomeController");

use App\Controllers\HomeController;

// Point d'entrée simple pour la page d'accueil
$controller = new HomeController();
$controller->index();
