<?php
// tests/bootstrap.php

// Désactive la sortie de contenu pendant les tests
ob_start();

// Configure l'environnement de test
$_ENV['ENVIRONMENT'] = 'testing';

// Charge l'autoloader de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

// Initialise les variables de session sans démarrer de session réelle
$_SESSION = [];