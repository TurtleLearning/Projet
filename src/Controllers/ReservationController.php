<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Services\EmailService;
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
            error_log("Début de store()");
            // Récupération des données POST
            $data = [
                'nom' => trim(htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8')),
                'prenom' => trim(htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8')),
                'num_tel' => trim(htmlspecialchars($_POST['num_tel'], ENT_QUOTES, 'UTF-8')),
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
            error_log("Modèle Reservation créé");

            $reservation->save();
            error_log("Réservation sauvegardée");

            // Email
            error_log("Tentative de création du service email");
            $emailService = new EmailService();
            error_log("Service email créé");

            error_log("Tentative d'envoi d'email");
            $emailService->sendReservationConfirmation($data);
            error_log("Email envoyé");

            echo "Réservation réussie";

        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue";
            header("Location: https://www.cefii-developpements.fr/julien1410/Projet/public/reservation.php");
            exit();
        }
    }

    public function processReservation($data) {
        // Validation
        $this->validateData($data);

        // Création de la réservation
        $reservation = new Reservation($data);
        return $reservation->save();
    }
}