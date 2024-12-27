<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/autoload.php';

use App\Controllers\ReservationController;

try {
    // Debug
    error_log("Tentative de chargement de ReservationController");
    
    $controller = new ReservationController();
    
    error_log("Controller instancié avec succès");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("Exécution de store()");
        $controller->store();
    } else {
        error_log("Exécution de index()");
        $controller->index();
    }
    
} catch (Exception $e) {
    error_log("Erreur dans reservation.php : " . $e->getMessage());
    error_log("Trace : " . $e->getTraceAsString());
    echo "Une erreur est survenue. Veuillez réessayer plus tard.";
}