<?php
// tests/bootstrap.php

namespace Tests;

// Désactive la sortie de contenu pendant les tests
if (ob_get_level()) ob_end_clean();
ob_start();

// Configure l'environnement de test avec des constantes
define('TESTING_ENV', true);
define('TEST_DB_HOST', 'localhost');
define('TEST_DB_NAME', 'test_database');

// Charge l'autoloader de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

// Initialise les variables de session de manière sécurisée
session_start([
    'use_strict_mode' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
$_SESSION = [];
session_write_close();

// Nettoyage à la fin des tests
register_shutdown_function(function() {
    if (ob_get_level()) ob_end_clean();
});