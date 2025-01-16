<?php
namespace Tests\Config;

class TestDatabase {
    private static $instance = null;
    private $connection = null;

    private function __construct() {
        try {
            // Configuration de la base de données de test
            $host = 'sqlprive-pc2372-001.eu.clouddb.ovh.net:35167';
            $dbname = 'cefiidev1410'; // Base de données spécifique pour les tests
            $username = 'cefiidev1410';
            $password = '8YTq84Xay';

            $this->connection = new \PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Erreur de connexion à la base de test: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Méthode pour réinitialiser la base de test
    public function resetTestDatabase() {
        $sql = file_get_contents(__DIR__ . '/test_schema.sql');
        $this->connection->exec($sql);
    }

    // Méthode pour insérer des données de test
    public function seedTestData() {
        $sql = "INSERT INTO reservations_nuitees (
            nom, prenom, num_tel, email,
            quantite_nuit, quantite_repas_midi, quantite_repas_soir,
            date_debut, date_fin, nombre_total, dont_enfants, total_ttc
        ) VALUES (
            'Test', 'User', '0123456789', 'test@example.com',
            2, 1, 1, '2025-01-01', '2025-01-03', 2, 0, 300.00
        )";
        $this->connection->exec($sql);
    }
}