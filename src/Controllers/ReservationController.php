<?php
namespace App\src\controller;

use App\Models\Reservation;
use DateTime;
use Exception;

class ReservationController {
    public function index() {
        require BASE_PATH . '/public/reservationPage.php';
    }

    public function store() {
        // Après traitement réussi
        header('Location: reservationPage.php?success=1');
        exit;
    }

    public function processReservation($data) {
        // Validation
        $this->validateData($data);
        
        // Création de la réservation
        $reservation = new Reservation($data);
        return $reservation->save();
    }
    
    private function validateData($data) {
        // Validation de l'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide");
        }

        // Validation des dates
        if (empty($data['date_debut']) || empty($data['date_fin'])) {
            throw new Exception("Les dates sont requises");
        }

        $dateDebut = new DateTime($data['date_debut']);
        $dateFin = new DateTime($data['date_fin']);

        if ($dateDebut > $dateFin) {
            throw new Exception("La date de fin doit être postérieure à la date de début");
        }

        // Validation des quantités
        if ($data['nombre_total'] < 1) {
            throw new Exception("Le nombre total de personnes doit être d'au moins 1");
        }

        if ($data['dont_enfants'] < 0) {
            throw new Exception("Le nombre d'enfants ne peut pas être négatif");
        }

        if ($data['dont_enfants'] >= $data['nombre_total']) {
            throw new Exception("Le nombre d'enfants ne peut pas être supérieur au nombre total de personnes");
        }
    }
}
