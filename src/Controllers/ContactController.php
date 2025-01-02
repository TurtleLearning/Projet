<?php
namespace App\Controllers;

use App\Models\Contact;
use Exception;

class ContactController {
    
    private $admin_email = "julinho52@hotmail.fr";
    private $contactModel;
    
    public function index() {
        $pageTitle = "Contact - Le Petit Chalet dans La Montagne";
        require BASE_PATH . '/views/contact/index.php';
    }

    public function __construct() {
        $this->contactModel = new Contact();
    }

    public function store() {
        try {
            // Protection contre les soumissions multiples
            if (isset($_SESSION['last_submission_time']) && 
                time() - $_SESSION['last_submission_time'] < 60) {
                throw new Exception("Veuillez attendre une minute entre chaque envoi");
            }

            // Validation des données
            $nom = trim(filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING));
            $contact = trim(filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING));
            $thematique = filter_input(INPUT_POST, 'thematique', FILTER_SANITIZE_STRING);
            $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

                // Vérifications plus strictes
            if (!preg_match('/^[A-Za-zÀ-ÿ\s-]{2,50}$/', $nom)) {
                throw new Exception("Le format du nom n'est pas valide");
            }

            // Validation email ou téléphone
            $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
            $isPhone = preg_match('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $contact);

            if (!$isEmail && !$isPhone) {
                throw new Exception("Le format du contact n'est pas valide");
            }

            // Validation de la longueur du message
            if (strlen($message) < 10 || strlen($message) > 1000) {
                throw new Exception("Le message doit contenir entre 10 et 1000 caractères");
            }

            // Validation de la thématique
            $thematiqueValides = ['reservation', 'activites', 'divers'];
            if (!in_array($thematique, $thematiqueValides)) {
                throw new Exception("La thématique sélectionnée n'est pas valide");
            }

            // Enregistrement du timestamp de soumission
            $_SESSION['last_submission_time'] = time();

            // Sauvegarde en BDD
            $this->contactModel->create($nom, $contact, $thematique, $message);

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