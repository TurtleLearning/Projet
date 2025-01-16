<?php
namespace App\Models;

use PDOException;
use Exception;
use App\Config\Database;
use App\Config\CSRFProtection;

class Contact {
    private $db;
    private $nom;
    private $contact;
    private $thematique;
    private $message;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function setData($nom, $contact, $thematique, $message) {
        $this->nom = trim($nom);
        $this->contact = trim($contact);
        $this->thematique = trim($thematique);
        $this->message = trim($message);
    }

    private function validate() {
        if (!preg_match('/^[A-Za-zÀ-ÿ\s-]{2,50}$/', $this->nom)) {
            throw new Exception("Le format du nom n'est pas valide");
        }

        $isEmail = filter_var($this->contact, FILTER_VALIDATE_EMAIL);
        $isPhone = preg_match('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $this->contact);
        if (!$isEmail && !$isPhone) {
            throw new Exception("Le format du contact n'est pas valide");
        }

        if (strlen($this->message) < 10 || strlen($this->message) > 1000) {
            throw new Exception("Le message doit contenir entre 10 et 1000 caractères");
        }

        $thematiqueValides = ['reservation', 'activites', 'divers'];
        if (!in_array($this->thematique, $thematiqueValides)) {
            throw new Exception("La thématique sélectionnée n'est pas valide");
        }
    }

    public function create($nom, $contact, $thematique, $message) {

        CSRFProtection::verifyToken($_POST['csrf_token']);

        $this->setData($nom, $contact, $thematique, $message);
        $this->validate();

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO contacts   (nom, contact, thematique, message)
                VALUES                  (:nom, :contact, :thematique, :message)");
            return $stmt->execute([
                'nom' => $this->nom,
                'contact' => $this->contact,
                'thematique' => $this->thematique,
                'message' => $this->message
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la sauvegarde du message: " . $e->getMessage());
        }
    }
}