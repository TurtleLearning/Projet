<?php
// reservation.php
require 'config.php';

function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = validateInput($_POST['nom']);
    $prenom = validateInput($_POST['prenom']);
    $telephone = validateInput($_POST['telephone']);
    $email = validateInput($_POST['email']);
    $quantiteNuit = (int)$_POST['quantite-nuit'];
    $quantiteRepasMidi = (int)$_POST['quantite-Repas-midi'];
    $quantiteRepasSoir = (int)$_POST['quantite-Repas-soir'];

    // Validation des données
    if (empty($nom) || empty($telephone) || empty($email)) {
        die("Tous les champs obligatoires doivent être remplis.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    if (!preg_match('/^[0-9]{10}$/', $telephone)) {
        die("Numéro de téléphone invalide.");
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO reservations (nom, prenom, telephone, email, quantite_nuit, quantite_repas_midi, quantite_repas_soir) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nom, $prenom, $telephone, $email, $quantiteNuit, $quantiteRepasMidi, $quantiteRepasSoir])) {
        echo "Réservation réussie.";
    } else {
        echo "Erreur lors de la réservation.";
    }
}
?>
