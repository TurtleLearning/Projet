<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Reservation;
use App\Config\CSRFProtection;
use PDO;
use PDOStatement;
use ReflectionClass;
use DateTime;
use \Exception as Exception;

class ReservationTest extends TestCase
{
    private $validData;
    private $dbMock;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Simulation d'une session PHP
        $_SESSION = [];
        
        $this->validData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'num_tel' => '0123456789',
            'email' => 'test@example.com',
            'quantite_nuit' => 2,
            'quantite_repas_midi' => 1,
            'quantite_repas_soir' => 1,
            'date_debut' => '2025-01-10',
            'date_fin' => '2025-01-12',
            'nombre_total' => 2,
            'dont_enfants' => 1,
            'total_ttc' => 120.00
        ];

        // Préparation du mock de la base de données
        $this->dbMock = $this->createMock(PDO::class);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }

    private function injectMockDatabase($reservation)
    {
        $reflectionClass = new ReflectionClass(Reservation::class);
        $reflectionProperty = $reflectionClass->getProperty('db');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($reservation, $this->dbMock);
    }

    public function testCreationReservationValide()
    {
        $reservation = new Reservation($this->validData);
        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertEquals($this->validData['nom'], $reservation->nom);
        $this->assertEquals($this->validData['email'], $reservation->email);
    }

    public function testValidationNomInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le nom et prénom doivent contenir entre 2 et 50 caractères alphabétiques");
        
        $invalidData = $this->validData;
        $invalidData['nom'] = '123';
        new Reservation($invalidData);
    }

    public function testValidationPrenomInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le nom et prénom doivent contenir entre 2 et 50 caractères alphabétiques");
        
        $invalidData = $this->validData;
        $invalidData['prenom'] = 'A';  // Trop court
        new Reservation($invalidData);
    }

    public function testValidationEmailInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Format d'email invalide");
        
        $invalidData = $this->validData;
        $invalidData['email'] = 'invalid.email';
        new Reservation($invalidData);
    }

    public function testValidationTelephoneInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le numéro de téléphone doit contenir exactement 10 chiffres");
        
        $invalidData = $this->validData;
        $invalidData['num_tel'] = '123';
        new Reservation($invalidData);
    }

    public function testValidationDatesInvalides()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("La date de fin doit être postérieure à la date de début");
        
        $invalidData = $this->validData;
        $invalidData['date_debut'] = '2025-01-12';
        $invalidData['date_fin'] = '2025-01-10';
        new Reservation($invalidData);
    }

    public function testValidationNombreEnfantsInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Nombre d'enfants invalide");
        
        $invalidData = $this->validData;
        $invalidData['nombre_total'] = 2;
        $invalidData['dont_enfants'] = 3;
        new Reservation($invalidData);
    }

    public function testValidationQuantiteNuitInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le nombre total de personnes doit être d'au moins 1");
        
        $invalidData = $this->validData;
        $invalidData['nombre_total'] = 0;
        new Reservation($invalidData);
    }

    public function testSauvegardeReservation()
    {
        // Configuration du token CSRF
        $_SESSION['csrf_token'] = 'test_token';
        $_POST['csrf_token'] = 'test_token';
        
        $reservation = new Reservation($this->validData);
        
        // Préparation du mock pour PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
                 
        $this->dbMock->expects($this->once())
                     ->method('prepare')
                     ->willReturn($stmtMock);
        
        $this->injectMockDatabase($reservation);
        
        $result = $reservation->save();
        $this->assertTrue($result);
    }

    public function testTokenCSRFInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Token CSRF invalide");
        
        $_SESSION['csrf_token'] = 'valid_token';
        $_POST['csrf_token'] = 'invalid_token';
        
        $reservation = new Reservation($this->validData);
        $this->injectMockDatabase($reservation);
        $reservation->save();
    }

    public function testValidationDatesManquantes()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Les dates de début et de fin sont requises");
        
        $invalidData = $this->validData;
        unset($invalidData['date_debut']);
        new Reservation($invalidData);
    }

    public function testValidationNombreTotalPersonnesInvalide()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le nombre total de personnes doit être d'au moins 1");
        
        $invalidData = $this->validData;
        $invalidData['nombre_total'] = -1;
        new Reservation($invalidData);
    }
}