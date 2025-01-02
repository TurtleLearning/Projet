<?php

namespace App\Models;

use PDOException;
use Exception;
use App\Config\Database;

class Contact {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($nom, $contact, $thematique, $message) {
        $query = "INSERT INTO contacts (nom, contact, thematique, message, date_creation) 
                  VALUES (:nom, :contact, :thematique, :message, NOW())";
        
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'nom' => $nom,
                'contact' => $contact,
                'thematique' => $thematique,
                'message' => $message
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la sauvegarde du message: " . $e->getMessage());
        }
    }
}