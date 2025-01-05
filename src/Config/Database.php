<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    // Instance unique de la classe (pattern Singleton)
    private static $instance = null;
    // Objet PDO pour la connexion
    private $pdo;
    
    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {
        // Charge les constantes de configuration
        $config = require 'constants.php';
        // Crée la chaîne de connexion
        $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset={$config['DB_CHARSET']}";
        
        try {
            // Initialise la connexion PDO avec des options sécurisées
            $this->pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lance des exceptions en cas d'erreur
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats en tableau associatif
                PDO::ATTR_EMULATE_PREPARES => false // Désactive l'émulation des requêtes préparées
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Erreur de connexion à la base de données");
        }
    }
    
    // Méthode pour obtenir l'instance unique
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Méthode pour obtenir la connexion PDO
    public function getConnection() {
        return $this->pdo;
    }
}