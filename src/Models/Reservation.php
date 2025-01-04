<?php
namespace App\Models;

use App\Config\Database;
use Exception;
use DateTime;

class Reservation {
    private $db;
    private $data;

    public $nom;
    public $prenom;
    public $num_tel;
    public $email;
    public $quantite_nuit;
    public $quantite_repas_midi;
    public $quantite_repas_soir;
    public $date_debut;
    public $date_fin;
    public $nombre_total;
    public $dont_enfants;
    public $total_ttc;

    public function __construct($data = null) {
        $this->db = Database::getInstance()->getConnection();
        $this->data = $data;

        if ($data) {
            $this->nom = $data['nom'];
            $this->prenom = $data['prenom'];
            $this->num_tel = $data['num_tel'];
            $this->email = $data['email'];
            $this->quantite_nuit = $data['quantite_nuit'];
            $this->quantite_repas_midi = $data['quantite_repas_midi'];
            $this->quantite_repas_soir = $data['quantite_repas_soir'];
            $this->date_debut = $data['date_debut'];
            $this->date_fin = $data['date_fin'];
            $this->nombre_total = $data['nombre_total'];
            $this->dont_enfants = $data['dont_enfants'];
            $this->total_ttc = $data['total_ttc'];
        }
    }

    private function validate() {
        if (!preg_match('/^[A-Za-zÀ-ÿ\s-]{2,50}$/', $this->nom) || 
            !preg_match('/^[A-Za-zÀ-ÿ\s-]{2,50}$/', $this->prenom)) {
            throw new Exception("Le nom et prénom doivent contenir entre 2 et 50 caractères alphabétiques");
        }

        if (!preg_match('/^\d{10}$/', $this->num_tel)) {
            throw new Exception("Le numéro de téléphone doit contenir exactement 10 chiffres");
        }

        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $this->email)) {
            throw new Exception("Format d'email invalide");
        }

        if ($this->nombre_total < 1) {
            throw new Exception("Le nombre total de personnes doit être d'au moins 1");
        }

        if ($this->dont_enfants < 0 || $this->dont_enfants >= $this->nombre_total) {
            throw new Exception("Nombre d'enfants invalide");
        }

        // Validation des dates
        if (empty($this->data['date_debut']) || empty($this->data['date_fin'])) {
            throw new Exception("Les dates de début et de fin sont requises.");
        }

        $dateDebut = new DateTime($data['date_debut']);
        $dateFin = new DateTime($data['date_fin']);
        if ($dateDebut > $dateFin) {
            throw new Exception("La date de fin doit être postérieure à la date de début.");
        }
    }

    // Méthode pour sauvegarder une réservation
    public function save() {
        $query = "INSERT INTO reservations_nuitees (
            nom, prenom, num_tel, email,
            quantite_nuit, quantite_repas_midi, quantite_repas_soir,
            date_debut, date_fin, nombre_total, dont_enfants, total_ttc
        ) VALUES (
            :nom, :prenom, :num_tel, :email,
            :quantite_nuit, :quantite_repas_midi, :quantite_repas_soir,
            :date_debut, :date_fin, :nombre_total, :dont_enfants, :total_ttc
        )";

        try {
            $this->validate(); 
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
                'dont_enfants' => $this->data['dont_enfants'],
                'total_ttc' => $this->data['total_ttc']
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erreur lors de la sauvegarde");
        }
    }
    
    public function getById($id) {
        $query = "SELECT * FROM reservations_nuitees WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    private function validateData() {
        if (empty($this->data['nom']) || empty($this->data['email']) || 
            empty($this->data['num_tel']) || empty($this->data['date_debut']) || 
            empty($this->data['date_fin'])) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }
        
        if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide");
        }
    }
}
