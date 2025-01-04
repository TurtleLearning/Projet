<?php
namespace App\Controllers;

use App\Models\Contact;
use Exception;

class ContactController {
   
   private $admin_email = "JBCEFii@gmail.com";
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
           if (isset($_SESSION['last_submission_time']) && 
               time() - $_SESSION['last_submission_time'] < 60) {
               throw new Exception("Veuillez attendre une minute entre chaque envoi");
           }

           // Protection anti-spam
           $_SESSION['last_submission_time'] = time();

           // Création et validation via le modèle
           $this->contactModel->create(
               $_POST['nom'],
               $_POST['contact'],
               $_POST['thematique'], 
               $_POST['message']
           );

           // Envoi des emails
           if (!$this->sendAdminEmail($_POST['nom'], $_POST['contact'], $_POST['thematique'], $_POST['message'])) {
               throw new Exception("Erreur lors de l'envoi du mail administrateur");
           }

           $this->sendUserConfirmation($_POST['nom'], $_POST['contact'], $_POST['thematique']);

           $_SESSION['message'] = "Votre message a été envoyé avec succès";
           $_SESSION['message_type'] = "success";
           header("Location: https://www.cefii-developpements.fr/julien1410/Projet/public/contact.php");
           exit();

       } catch (Exception $e) {
           $_SESSION['message'] = $e->getMessage();
           $_SESSION['message_type'] = "danger";
           header("Location: https://www.cefii-developpements.fr/julien1410/Projet/public/contact.php");
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