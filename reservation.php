<?php
// reservation.php
require 'config.php';
date_default_timezone_set('Europe/Paris');

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
    $nombreTotal = (int)$_POST['nombre-total'];
    $nombreEnfants = (int)$_POST['nombre-enfants'];
    
    // Récupération et formatage des dates
    $dateDebut = validateInput($_POST['date-debut']);
    $dateFin = validateInput($_POST['date-fin']);
    
    // Conversion du format "d/m/Y" vers "Y-m-d" pour la BDD
    $dateDebut = DateTime::createFromFormat('d/m/Y', $dateDebut)->format('Y-m-d');
    $dateFin = DateTime::createFromFormat('d/m/Y', $dateFin)->format('Y-m-d');

    try {
        // Insertion directe sans vérification
        $stmt = $pdo->prepare("INSERT INTO reservations_nuitees (
            nom, 
            prenom, 
            num_tel, 
            email, 
            quantite_nuit, 
            quantite_repas_midi, 
            quantite_repas_soir, 
            date_debut, 
            date_fin, 
            nombre_total, 
            nombre_enfants
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([
            $nom, 
            $prenom, 
            $num_tel, 
            $email, 
            $quantiteNuit, 
            $quantiteRepasMidi, 
            $quantiteRepasSoir, 
            $dateDebut, 
            $dateFin, 
            $nombreTotal, 
            $nombreEnfants,
        ])) {
            echo "Réservation réussie.";
        } else {
            throw new Exception("Erreur lors de la réservation");
        }
    } catch (Exception $e) {
        echo "Une erreur est survenue : " . $e->getMessage();
    }
}
?>
