<?php

require_once dirname(__DIR__) . '/src/autoload.php';

use App\Controllers\ContactController;

try {
    $controller = new ContactController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } else {
        $controller->index();
    }
} catch (Exception $e) {
    error_log("Erreur dans contact.php : " . $e->getMessage());
    echo "Une erreur est survenue. Veuillez réessayer plus tard.";
}
