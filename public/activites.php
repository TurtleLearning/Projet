<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/src/autoload.php';

use App\Controllers\ActivitesController;

try {
    // Debug
    error_log("Tentative de chargement de ActivitesController");
    
    $controller = new ActivitesController();
    
    error_log("Controller instancié avec succès");
    
    $controller->index();
    
    error_log("Méthode index() exécutée");
    
} catch (Exception $e) {
    error_log("Erreur dans activites.php : " . $e->getMessage());
    error_log("Trace : " . $e->getTraceAsString());
    echo "Une erreur est survenue. Veuillez réessayer plus tard.";
}