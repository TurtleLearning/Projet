<?php

namespace App\Controllers;

use App\Models\Reservation;
use DateTime;
use Exception;

class ReservationController {
    public function index() {
        try {
            // Debug
            error_log("Début de ReservationController::index()");
            
            $pageTitle = "Réservation - Le Petit Chalet dans La Montagne";
            
            // Debug
            error_log("Chargement de la vue reservation/index.php");
            
            require BASE_PATH . '/views/reservation/index.php';
            
            error_log("Vue chargée avec succès");
            
        } catch (\Exception $e) {
            error_log("Erreur dans ReservationController::index() : " . $e->getMessage());
            error_log("Trace : " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function store() {
        try {
            // Récupération des données POST
            $data = [
                'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING),
                'prenom' => filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING),
                'num_tel' => filter_input(INPUT_POST, 'num_tel', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'quantite_nuit' => filter_input(INPUT_POST, 'quantite_nuit', FILTER_SANITIZE_NUMBER_INT),
                'quantite_repas_midi' => filter_input(INPUT_POST, 'quantite_repas_midi', FILTER_SANITIZE_NUMBER_INT),
                'quantite_repas_soir' => filter_input(INPUT_POST, 'quantite_repas_soir', FILTER_SANITIZE_NUMBER_INT),
                'date_debut' => filter_input(INPUT_POST, 'date_debut', FILTER_SANITIZE_STRING),
                'date_fin' => filter_input(INPUT_POST, 'date_fin', FILTER_SANITIZE_STRING),
                'nombre_total' => filter_input(INPUT_POST, 'nombre_total', FILTER_SANITIZE_NUMBER_INT),
                'dont_enfants' => filter_input(INPUT_POST, 'dont_enfants', FILTER_SANITIZE_NUMBER_INT),
                'total_ttc' => filter_input(INPUT_POST, 'total_ttc')
            ];

            // Validation des données
            if (empty($data['quantite_nuit'])) {
                throw new Exception("La quantité de nuit est requise.");
            }
            if (empty($data['nombre_total'])) {
                throw new Exception("Le nombre total de personnes est requis.");
            }

            // Log des données pour débogage
            error_log("Données récupérées : " . print_r($data, true));

            // Instancier le modèle Reservation
            $reservation = new Reservation($data);
            $reservation->save(); // Appeler la méthode pour sauvegarder

            // Réponse pour le fetch
            echo "Réservation réussie"; // Réponse à envoyer au fetch
        } catch (Exception $e) {
            error_log("Erreur dans ReservationController::store() : " . $e->getMessage());
            echo "Erreur lors de la réservation : " . $e->getMessage(); // Afficher le message d'erreur
        }
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

        // Validation des quantités
        if (empty($data['quantite_nuit'])) {
            throw new Exception("La quantité de nuit est requise");
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