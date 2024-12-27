<?php
namespace App\Controllers;

class ContactController {
    public function index() {

        $pageTitle = "Contact - Le Petit Chalet dans La Montagne";
        require BASE_PATH . '/views/contact/index.php';
    }

    public function store() {
        try {
            // Récupérer et nettoyer les données du formulaire
            $data = [
                'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING),
                'prenom' => filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING),
                'num_tel' => filter_input(INPUT_POST, 'num_tel', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'quantite_nuit' => filter_input(INPUT_POST, 'quantite-nuit', FILTER_SANITIZE_NUMBER_INT),
                'quantite_repas_midi' => filter_input(INPUT_POST, 'quantite-Repas-midi', FILTER_SANITIZE_NUMBER_INT),
                'quantite_repas_soir' => filter_input(INPUT_POST, 'quantite-Repas-soir', FILTER_SANITIZE_NUMBER_INT),
                'date_debut' => filter_input(INPUT_POST, 'date-debut', FILTER_SANITIZE_STRING),
                'date_fin' => filter_input(INPUT_POST, 'date-fin', FILTER_SANITIZE_STRING),
                'nombre_total' => filter_input(INPUT_POST, 'nombre-total', FILTER_SANITIZE_NUMBER_INT),
                'dont_enfants' => filter_input(INPUT_POST, 'nombre-enfants', FILTER_SANITIZE_NUMBER_INT)
            ];
        
            // Validation supplémentaire
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
            
            // Utiliser le contrôleur pour traiter la réservation
            $result = $controller->processReservation($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Réservation réussie']);
            } else {
                throw new Exception("Erreur lors de la réservation");
            }
        
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        // Rediriger avec un message de succès
        header('Location: contact.php?success=1');
        exit;
    }
}
