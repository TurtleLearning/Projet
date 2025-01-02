<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/autoload.php';

use App\Controllers\ContactController;

try {
    $controller = new ContactController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } else {
        $controller->index();
    }
} catch (Exception $e) {
    $_SESSION['message'] = "Une erreur est survenue : " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header("Location: " . BASE_PATH . "/views/contact/index.php");
    exit();
}