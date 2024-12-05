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

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO reservations_nuitees (nom, prenom, num_tel, email, quantite_nuit, quantite_repas_midi, quantite_repas_soir) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nom, $prenom, $num_tel, $email, $quantiteNuit, $quantiteRepasMidi, $quantiteRepasSoir])) {
        echo "Réservation réussie.";
    } else {
        echo "Erreur lors de la réservation : " . implode(", ", $stmt->errorInfo());
    }
}
?>
