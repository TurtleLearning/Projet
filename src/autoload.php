<?php

/* La fonction d'autoload construit le chemin du fichier à partir du nom de la classe */

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Ajout d'un log pour debug
    error_log("Tentative de chargement du fichier : " . $file);

    if (file_exists($file)) {
        require $file;
    } else {
        error_log("Fichier non trouvé : " . $file);
    }
});

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
