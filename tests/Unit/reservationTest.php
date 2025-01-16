<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Reservation;
use PDO;

class ReservationTest extends TestCase
{
    private $db;
    private $pdo;
    private array $validReservationData;

    protected function setUp(): void
    {
        parent::setUp();

        // Configuration de la base de données de test
        $host = 'sqlprive-pc2372-001.eu.clouddb.ovh.net:35167';
        $dbname = 'cefiidev1410';
        $username = 'cefiidev1410';
        $password = '8YTq84Xay';

        // Connexion à la base de données de test
        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            // Réinitialisation de la table de test
            $this->resetTestTable();

        } catch (\PDOException $e) {
            $this->fail("Erreur de connexion à la base de test: " . $e->getMessage());
        }

        // Données de test valides
        $this->validReservationData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'num_tel' => '0123456789',
            'email' => 'jean.dupont@example.com',
            'quantite_nuit' => 3,
            'quantite_repas_midi' => 2,
            'quantite_repas_soir' => 2,
            'date_debut' => '2025-02-01',
            'date_fin' => '2025-02-04',
            'nombre_total' => 2,
            'dont_enfants' => 0,
            'total_ttc' => 450.00,
            'csrf_token' => 'token_test'
        ];
    }

    private function resetTestTable(): void
    {
        $sql = "DROP TABLE IF EXISTS reservations_nuiteees;
                CREATE TABLE reservations_nuiteees (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nom VARCHAR(50) NOT NULL,
                    prenom VARCHAR(50) NOT NULL,
                    num_tel VARCHAR(10) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    quantite_nuit INT NOT NULL,
                    quantite_repas_midi INT DEFAULT 0,
                    quantite_repas_soir INT DEFAULT 0,
                    date_debut DATE NOT NULL,
                    date_fin DATE NOT NULL,
                    nombre_total INT NOT NULL,
                    dont_enfants INT DEFAULT 0,
                    total_ttc DECIMAL(10,2) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $this->pdo->exec($sql);
    }

    /**
     * Test de la création d'une réservation valide
     */
    public function testCreationReservationValide(): void
    {
        $reservation = new Reservation($this->validReservationData);
        
        try {
            $result = $reservation->save();
            $this->assertTrue($result, "La sauvegarde de la réservation a échoué");

            // Vérification en base de données
            $stmt = $this->pdo->prepare("SELECT * FROM reservations_nuiteees WHERE email = ?");
            $stmt->execute([$this->validReservationData['email']]);
            $savedReservation = $stmt->fetch();

            $this->assertNotFalse($savedReservation, "La réservation n'a pas été trouvée en base");
            $this->assertEquals($this->validReservationData['nom'], $savedReservation['nom']);
            $this->assertEquals($this->validReservationData['email'], $savedReservation['email']);
            $this->assertEquals($this->validReservationData['total_ttc'], $savedReservation['total_ttc']);
        } catch (\Exception $e) {
            $this->fail("Exception inattendue : " . $e->getMessage());
        }
    }

    /**
     * Test des validations de données invalides
     */
    public function testValidationDonneesInvalides(): void
    {
        $invalidDataSets = [
            'nom_invalide' => array_merge($this->validReservationData, ['nom' => 'J@']),
            'email_invalide' => array_merge($this->validReservationData, ['email' => 'email_invalide']),
            'telephone_invalide' => array_merge($this->validReservationData, ['num_tel' => '123']),
            'dates_invalides' => array_merge($this->validReservationData, [
                'date_debut' => '2025-02-04',
                'date_fin' => '2025-02-01'
            ])
        ];

        foreach ($invalidDataSets as $type => $invalidData) {
            try {
                $reservation = new Reservation($invalidData);
                $reservation->save();
                $this->fail("Une exception aurait dû être levée pour $type");
            } catch (\Exception $e) {
                $this->assertTrue(true, "Exception correctement levée pour $type");
            }
        }
    }

    /**
     * Test de calcul du nombre de nuits
     */
    public function testCalculNombreNuits(): void
    {
        $reservation = new Reservation($this->validReservationData);
        $this->assertEquals(3, $reservation->quantite_nuit, "Le calcul du nombre de nuits est incorrect");
    }

    /**
     * Test de validation du nombre de personnes
     */
    public function testValidationNombrePersonnes(): void
    {
        // Test avec plus d'enfants que le total
        $invalidData = array_merge($this->validReservationData, [
            'nombre_total' => 2,
            'dont_enfants' => 3
        ]);

        $this->expectException(\Exception::class);
        $reservation = new Reservation($invalidData);
        $reservation->save();
    }

    /**
     * Test de calcul du total TTC
     */
    public function testCalculTotalTTC(): void
    {
        $reservation = new Reservation($this->validReservationData);
        $expectedTotal = 450.00; // Calculé selon vos règles de gestion
        $this->assertEquals($expectedTotal, $reservation->total_ttc, "Le calcul du total TTC est incorrect");
    }

    /**
     * Test de validation des dates de réservation
     */
    public function testValidationDates(): void
    {
        // Test avec des dates dans le passé
        $invalidData = array_merge($this->validReservationData, [
            'date_debut' => '2023-01-01',
            'date_fin' => '2023-01-04'
        ]);

        $this->expectException(\Exception::class);
        $reservation = new Reservation($invalidData);
        $reservation->save();
    }

    /**
     * Test de la protection CSRF
     */
    public function testProtectionCSRF(): void
    {
        $dataWithoutToken = $this->validReservationData;
        unset($dataWithoutToken['csrf_token']);

        $this->expectException(\Exception::class);
        $reservation = new Reservation($dataWithoutToken);
        $reservation->save();
    }

    /**
     * Test de la mise à jour d'une réservation
     */
    public function testMiseAJourReservation(): void
    {
        // Création initiale
        $reservation = new Reservation($this->validReservationData);
        $reservation->save();

        // Récupération de l'ID
        $stmt = $this->pdo->prepare("SELECT id FROM reservations_nuiteees WHERE email = ?");
        $stmt->execute([$this->validReservationData['email']]);
        $id = $stmt->fetchColumn();

        // Modification des données
        $updatedData = array_merge($this->validReservationData, [
            'quantite_repas_midi' => 3,
            'total_ttc' => 500.00
        ]);

        // Mise à jour et vérification
        $updatedReservation = new Reservation($updatedData);
        $updatedReservation->update($id);

        // Vérification en base
        $stmt = $this->pdo->prepare("SELECT * FROM reservations_nuiteees WHERE id = ?");
        $stmt->execute([$id]);
        $savedData = $stmt->fetch();

        $this->assertEquals(3, $savedData['quantite_repas_midi']);
        $this->assertEquals(500.00, $savedData['total_ttc']);
    }

    /**
     * Test de suppression d'une réservation
     */
    public function testSuppressionReservation(): void
    {
        // Création d'une réservation
        $reservation = new Reservation($this->validReservationData);
        $reservation->save();

        // Récupération de l'ID
        $stmt = $this->pdo->prepare("SELECT id FROM reservations_nuiteees WHERE email = ?");
        $stmt->execute([$this->validReservationData['email']]);
        $id = $stmt->fetchColumn();

        // Suppression
        $reservation->delete($id);

        // Vérification que la réservation n'existe plus
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations_nuiteees WHERE id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        $this->assertEquals(0, $count, "La réservation n'a pas été supprimée");
    }

    protected function tearDown(): void
    {
        // Nettoyage après chaque test
        $this->pdo->exec("TRUNCATE TABLE reservations_nuiteees");
        $this->pdo = null;
        parent::tearDown();
    }
}