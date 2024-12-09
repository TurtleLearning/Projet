<?php
// reservation.php
require 'config.php';

function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = validateInput($_POST['nom']);
    $prenom = validateInput($_POST['prenom']);
    $num_tel = validateInput($_POST['num_tel']);
    $email = validateInput($_POST['email']);
    $quantiteNuit = (int)$_POST['quantite-nuit'];
    $quantiteRepasMidi = (int)$_POST['quantite-Repas-midi'];
    $quantiteRepasSoir = (int)$_POST['quantite-Repas-soir'];
    
    // Récupération et formatage des dates
    $dateDebut = validateInput($_POST['date-debut']);
    $dateFin = validateInput($_POST['date-fin']);
    
    // Conversion du format "d / m / Y" vers "Y-m-d" pour la BDD
    $dateDebut = DateTime::createFromFormat('d / m / Y', $dateDebut)->format('Y-m-d');
    $dateFin = DateTime::createFromFormat('d / m / Y', $dateFin)->format('Y-m-d');

    // Validation des données
    if (empty($nom) || empty($num_tel) || empty($email)) {
        echo "Tous les champs obligatoires doivent être remplis.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $num_tel)) {
        echo "Numéro de téléphone invalide.";
        exit;
    }

    // Ajout de la validation des quantités
    if ($quantiteNuit < 0 || $quantiteRepasMidi < 0 || $quantiteRepasSoir < 0) {
        echo "Les quantités ne peuvent pas être négatives.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations_nuitees (nom, prenom, num_tel, email, quantite_nuit, quantite_repas_midi, quantite_repas_soir, date_debut, date_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$nom, $prenom, $num_tel, $email, $quantiteNuit, $quantiteRepasMidi, $quantiteRepasSoir, $dateDebut, $dateFin])) {
            echo "Réservation réussie.";
        } else {
            throw new Exception("Erreur lors de la réservation");
        }
    } catch (Exception $e) {
        echo "Une erreur est survenue : " . $e->getMessage();
    }
}
?>
