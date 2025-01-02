<?php
namespace App\Models;

use App\Config\Database; // Assurez-vous d'importer la classe Database

class Reservation {
    private $db;
    private $data;
    
    public function __construct($data = null) {
        $this->db = Database::getInstance()->getConnection();
        $this->data = $data;
    }
    
    // Méthode pour sauvegarder une réservation
    public function save() {
        $query = "INSERT INTO reservations_nuitees (
            nom, prenom, num_tel, email,
            quantite_nuit, quantite_repas_midi, quantite_repas_soir,
            date_debut, date_fin, nombre_total, dont_enfants
        ) VALUES (
            :nom, :prenom, :num_tel, :email,
            :quantite_nuit, :quantite_repas_midi, :quantite_repas_soir,
            :date_debut, :date_fin, :nombre_total, :dont_enfants
        )";
        
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'nom' => $this->data['nom'],
                'prenom' => $this->data['prenom'],
                'num_tel' => $this->data['num_tel'],
                'email' => $this->data['email'],
                'quantite_nuit' => $this->data['quantite_nuit'],
                'quantite_repas_midi' => $this->data['quantite_repas_midi'],
                'quantite_repas_soir' => $this->data['quantite_repas_soir'],
                'date_debut' => $this->data['date_debut'],
                'date_fin' => $this->data['date_fin'],
                'nombre_total' => $this->data['nombre_total'],
                'dont_enfants' => $this->data['dont_enfants']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la sauvegarde de la réservation");
        }
    }
    
    // Méthode pour récupérer une réservation par ID
    public function getById($id) {
        $query = "SELECT * FROM reservations_nuitees WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
