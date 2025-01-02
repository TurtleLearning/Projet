<?php
namespace App\Controllers;

use Exception;

class ContactController {
    private $admin_email = "julinho52@hotmail.fr";

    public function index() {
        $pageTitle = "Contact - Le Petit Chalet dans La Montagne";
        require BASE_PATH . '/views/contact/index.php';
    }

    public function store() {
        try {
            // Validation des données
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_EMAIL);
            $thematique = filter_input(INPUT_POST, 'thematique', FILTER_SANITIZE_STRING);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

            // Vérifications
            if (!$nom || !$contact || !$thematique || !$message) {
                throw new Exception("Tous les champs sont obligatoires");
            }

            if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("L'adresse email n'est pas valide");
            }

            // Envoi email admin
            if (!$this->sendAdminEmail($nom, $contact, $thematique, $message)) {
                throw new Exception("Erreur lors de l'envoi du mail administrateur");
            }

            // Envoi confirmation utilisateur
            $this->sendUserConfirmation($nom, $contact, $thematique);

            // Message de succès
            $_SESSION['message'] = "Votre message a été envoyé avec succès";
            $_SESSION['message_type'] = "success";

            // Redirection
            header("Location: https://www.cefii-developpements.fr/julien1410/Projet+templates/public/contact.php");
            exit();

        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = "danger";
            header("Location: https://www.cefii-developpements.fr/julien1410/Projet+templates/public/contact.php");
            exit();
        }
    }

    private function sendAdminEmail($nom, $contact, $thematique, $message) {
        $subject = "Nouveau message - " . $thematique;
        $content = "Nouveau message reçu :\n\n";
        $content .= "Nom : " . $nom . "\n";
        $content .= "Contact : " . $contact . "\n";
        $content .= "Thématique : " . $thematique . "\n\n";
        $content .= "Message :\n" . $message;

        $headers = "From: " . $contact . "\r\n";
        $headers .= "Reply-To: " . $contact . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        return mail($this->admin_email, $subject, $content, $headers);
    }

    private function sendUserConfirmation($nom, $email, $thematique) {
        $subject = "Confirmation de réception - Le Petit Chalet dans La Montagne";
        $content = "Bonjour " . $nom . ",\n\n";
        $content .= "Nous avons bien reçu votre message concernant : " . $thematique . "\n\n";
        $content .= "Nous vous répondrons dans les plus brefs délais.\n\n";
        $content .= "Cordialement,\n";
        $content .= "L'équipe du Petit Chalet dans La Montagne";

        $headers = "From: " . $this->admin_email . "\r\n";
        $headers .= "Reply-To: " . $this->admin_email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        return mail($email, $subject, $content, $headers);
    }
}