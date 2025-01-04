<?php
namespace App\Services;

require_once BASE_PATH . '/lib/PHPMailer/Exception.php';
require_once BASE_PATH . '/lib/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailService {
    private $mailer;
    private $prices = [
        'nuit_adulte' => 15,
        'nuit_enfant' => 5,
        'repas_midi' => 7,
        'repas_soir' => 15
    ];
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        try {

            $this->loadEnv();

            $host = getenv('SMTP_HOST');
            $username = getenv('SMTP_USERNAME');
            $password = getenv('SMTP_PASSWORD_APP');
            
            if (!$host || !$username || !$password) {
                throw new Exception("Configuration SMTP manquante dans .env");
            }

            error_log("Configuration SMTP trouvée : " . json_encode([
                'host' => $host,
                'username' => $username,
                'port' => getenv('SMTP_PORT')
            ]));

            // Débogage des variables d'environnement
            error_log("SMTP_HOST: " . getenv('SMTP_HOST'));
            error_log("SMTP_USERNAME: " . getenv('SMTP_USERNAME'));
            error_log("SMTP_PORT: " . getenv('SMTP_PORT'));
            error_log("SMTP_FROM_EMAIL: " . getenv('SMTP_FROM_EMAIL'));
            error_log("ADMIN_EMAIL: " . getenv('ADMIN_EMAIL'));

            // Configuration du serveur SMTP avec les variables d'environnement
            $this->mailer->isSMTP();
            $this->mailer->SMTPDebug = 2;
            $this->mailer->Host = getenv('SMTP_HOST');
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = getenv('SMTP_USERNAME');
            $this->mailer->Password = getenv('SMTP_PASSWORD_APP');
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = getenv('SMTP_PORT');
            $this->mailer->CharSet = 'UTF-8';

            // Configuration de l'expéditeur depuis les variables d'environnement
            $this->mailer->setFrom(
                getenv('SMTP_FROM_EMAIL'), 
                getenv('SMTP_FROM_NAME')
            );
        } catch (Exception $e) {
            error_log("Erreur de configuration de l'email : " . $e->getMessage());
            throw $e;
        }
    }

    public function loadEnv($path = null) {
        if ($path === null) {
            $path = BASE_PATH . '/.env';
        }
        
        if (!file_exists($path)) {
            throw new Exception("Fichier .env introuvable : $path");
        }
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
    
            if (strpos($line, '=') !== false) {  // Vérifie si la ligne contient un '='
                list($name, $value) = explode('=', $line, 2);
                putenv(trim($name) . '=' . trim($value));
            }
        }
    }

    public function testConnection() {
        try {
            $this->mailer->smtpConnect();
            error_log("Connexion SMTP réussie");
            return true;
        } catch (Exception $e) {
            error_log("Erreur de connexion SMTP : " . $e->getMessage());
            return false;
        }
    }

    public function sendReservationConfirmation($reservation) {
        try {
            if (!$this->testConnection()) {
                throw new Exception("La connexion au serveur SMTP a échoué");
            }

            error_log("Début de l'envoi d'email");
            error_log("Configuration SMTP : " . print_r([
                'host' => $this->mailer->Host,
                'username' => $this->mailer->Username,
                'port' => $this->mailer->Port,
                'to' => $reservation['email']
            ], true));

            $safeData = array_map(function($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }, $reservation);

            $this->mailer->addAddress(filter_var($reservation['email'], FILTER_SANITIZE_EMAIL));
            error_log("Adresse destinataire ajoutée");

            $this->mailer->Subject = 'Confirmation de votre réservation - Le Petit Chalet dans La Montagne';
            $this->mailer->isHTML(true);

            $this->mailer->Body = $this->buildEmailBody($reservation);
            error_log("Corps de l'email construit");

            $this->mailer->addBCC(getenv('ADMIN_EMAIL'));
            error_log("Copie admin ajoutée");

            $result = $this->mailer->send();
            error_log("Email envoyé avec succès");

            return $result;
        } catch (Exception $e) {
            error_log("Erreur détaillée lors de l'envoi de l'email : " . $e->getMessage());
            error_log("Debug SMTP : " . $this->mailer->ErrorInfo);
            throw $e;
        }
    }

    private function buildEmailBody($reservation) {
        // Formatage des dates
        $dateDebut = date('d/m/Y', strtotime($reservation['date_debut']));
        $dateFin = date('d/m/Y', strtotime($reservation['date_fin']));

        // Calcul du nombre d'adultes
        $nombreAdultes = $reservation['nombre_total'] - $reservation['dont_enfants'];

        // Calcul des totaux
        $totalNuitsAdultes = $nombreAdultes * $reservation['quantite_nuit'] * $this->prices['nuit_adulte'];
        $totalNuitsEnfants = $reservation['dont_enfants'] * $reservation['quantite_nuit'] * $this->prices['nuit_enfant'];
        $totalRepasMidi = $reservation['quantite_repas_midi'] * $this->prices['repas_midi'];
        $totalRepasSoir = $reservation['quantite_repas_soir'] * $this->prices['repas_soir'];

        // Construction du template HTML
        return "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #2c3e50; text-align: center; margin-bottom: 30px;'>
                    Confirmation de réservation
                </h2>

                <div style='background: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;'>
                    <h3 style='color: #2c3e50; margin-top: 0;'>Détails du client</h3>
                    <p>
                        <strong>Nom :</strong> {$reservation['nom']}<br>
                        <strong>Prénom :</strong> {$reservation['prenom']}<br>
                        <strong>Email :</strong> {$reservation['email']}<br>
                        <strong>Téléphone :</strong> {$reservation['num_tel']}<br>
                    </p>
                </div>

                <div style='background: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;'>
                    <h3 style='color: #2c3e50; margin-top: 0;'>Informations de séjour</h3>
                    <p>
                        <strong>Date d'arrivée :</strong> {$dateDebut} (après 14h)<br>
                        <strong>Date de départ :</strong> {$dateFin} (avant 10h)<br>
                        <strong>Nombre de nuits :</strong> {$reservation['quantite_nuit']}<br>
                        <strong>Nombre total de personnes :</strong> {$reservation['nombre_total']}<br>
                        <strong>Dont enfants (-12 ans) :</strong> {$reservation['dont_enfants']}<br>
                    </p>
                </div>

                <div style='background: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;'>
                    <h3 style='color: #2c3e50; margin-top: 0;'>Détail des prestations</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr style='border-bottom: 1px solid #ddd;'>
                            <th style='text-align: left; padding: 8px;'>Description</th>
                            <th style='text-align: right; padding: 8px;'>Montant</th>
                        </tr>

                        <tr>
                            <td style='padding: 8px;'>Hébergement adultes ({$nombreAdultes} personnes x {$reservation['quantite_nuit']} nuits x {$this->prices['nuit_adulte']}€)</td>
                            <td style='text-align: right; padding: 8px;'>{$totalNuitsAdultes}€</td>
                        </tr>
                        " . ($reservation['dont_enfants'] > 0 ? "
                        <tr>
                            <td style='padding: 8px;'>Hébergement enfants ({$reservation['dont_enfants']} enfants x {$reservation['quantite_nuit']} nuits x {$this->prices['nuit_enfant']}€)</td>
                            <td style='text-align: right; padding: 8px;'>{$totalNuitsEnfants}€</td>
                        </tr>
                        " : "") . "
                        " . ($reservation['quantite_repas_midi'] > 0 ? "
                        <tr>
                            <td style='padding: 8px;'>Repas midi ({$reservation['quantite_repas_midi']} x {$this->prices['repas_midi']}€)</td>
                            <td style='text-align: right; padding: 8px;'>{$totalRepasMidi}€</td>
                        </tr>
                        " : "") . "
                        " . ($reservation['quantite_repas_soir'] > 0 ? "
                        <tr>
                            <td style='padding: 8px;'>Repas soir ({$reservation['quantite_repas_soir']} x {$this->prices['repas_soir']}€)</td>
                            <td style='text-align: right; padding: 8px;'>{$totalRepasSoir}€</td>
                        </tr>
                        " : "") . "
                        <tr style='border-top: 2px solid #ddd; font-weight: bold;'>
                            <td style='padding: 8px;'>TOTAL TTC</td>
                            <td style='text-align: right; padding: 8px;'>{$reservation['total_ttc']}€</td>
                        </tr>
                    </table>
                </div>

                <div style='background: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;'>
                    <h3 style='color: #2c3e50; margin-top: 0;'>Informations importantes</h3>
                    <ul style='margin-bottom: 0;'>
                        <li>Merci d'arriver après 14h le jour de votre arrivée</li>
                        <li>La chambre doit être libérée avant 10h le jour du départ</li>
                        <li>Le règlement s'effectuera sur place à votre arrivée</li>
                        <li>En cas d'annulation, merci de nous prévenir au moins 48h à l'avance sans quoi vous serez facturé</li>
                    </ul>
                </div>

                <p style='text-align: center; margin-top: 30px;'>
                    Pour toute question, n'hésitez pas à nous contacter :<br>
                    Tél : +33 (0)X XX XX XX XX<br>
                    Email : contact@lepetitchalet.com
                </p>

                <p style='text-align: center; color: #666; font-size: 0.9em; margin-top: 40px;'>
                    Merci de votre confiance !<br>
                    L'équipe du Petit Chalet dans La Montagne
                </p>
            </div>
        </body>
        </html>
        ";
    }
}