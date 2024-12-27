<?php

namespace App\Models;

require_once 'config/constants.php';

class Contact {
    private $db;
    private $data;
    
    public function __construct($data = null) {
        $this->db = Database::getInstance()->getConnection();
        $this->data = $data;
    }
    
    // Méthode pour sauvegarder un message de contact
    public function save() {
        $query = "INSERT INTO contacts (
            nom, contact, thematique, message, date_creation
        ) VALUES (
            :nom, :contact, :thematique, :message, NOW()
        )";
        
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'nom' => $this->data['nom'],
                'contact' => $this->data['contact'],
                'thematique' => $this->data['thematique'],
                'message' => $this->data['message']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la sauvegarde du message");
        }
    }
}
