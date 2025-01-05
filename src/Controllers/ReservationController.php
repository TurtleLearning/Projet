<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Services\EmailService;
use Exception;

class ReservationController {
    public function index() {
        try {

            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            $pageTitle = "Réservation - Le Petit Chalet dans La Montagne";
            require BASE_PATH . '/views/reservation/index.php';
    
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

            // Log des données pour débogage
            error_log("Données récupérées : " . print_r($data, true));

            // Instancier le modèle Reservation
            $reservation = new Reservation($data);
            error_log("Modèle Reservation créé");

            $reservation->save();
            echo json_encode(['status' => 'success', 'message' => 'Réservation réussie']);
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
            http_response_code(400); // Définit un code d'erreur HTTP
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage() ?: "Une erreur est survenue"
            ]);
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