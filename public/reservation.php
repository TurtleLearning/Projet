<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../src/autoload.php';

use App\Controllers\ReservationController;

try {
    // Debug de la session et du token
    error_log("Session ID: " . session_id());
    error_log("CSRF Token en session: " . ($_SESSION['csrf_token'] ?? 'non défini'));
    error_log("CSRF Token en POST: " . ($_POST['csrf_token'] ?? 'non défini'));

    $controller = new ReservationController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("Méthode POST détectée");
        error_log("Données POST: " . print_r($_POST, true));
        $controller->store();
    } else {
        $controller->index();
    }

} catch (Exception $e) {
    error_log("Erreur dans reservation.php : " . $e->getMessage());
    error_log("Trace : " . $e->getTraceAsString());

    // Débogage CSRF
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'details' => [
            'session_id' => session_id(),
            'has_token' => isset($_SESSION['csrf_token']),
            'post_token' => $_POST['csrf_token'] ?? 'non fourni'
        ]
    ]);
}