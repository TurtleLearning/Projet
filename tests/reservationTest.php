<?php

namespace Tests;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase {
    private $validData;
    private $dbMock;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock de Database::getInstance()
        $this->dbMock = $this->createMock(PDO::class);
        $dbInstance = $this->createMock(Database::class);
        $dbInstance->method('getConnection')->willReturn($this->dbMock);
        
        // Remplacer l'instance singleton
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, $dbInstance);
        
        $this->validData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            // ... autres données valides ...
        ];
    }

    public function testValidationPrenomInvalide()
    {
        $invalidData = $this->validData;
        $invalidData['prenom'] = 'A'; // Prénom trop court
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le nom et prénom doivent contenir entre 2 et 50 caractères alphabétiques");
        
        new Reservation($invalidData); // La validation se fait maintenant à la construction
    }

    // ... autres tests ...
    
    protected function tearDown(): void
    {
        // Réinitialiser l'instance singleton
        $reflection = new ReflectionClass(Database::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null, null);
        
        parent::tearDown();
    }
}