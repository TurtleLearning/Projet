<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM reservations_nuitees 
            WHERE (date_debut <= :dateFin AND date_fin >= :dateDebut)
        ");
        
        $stmt->execute([
            ':dateDebut' => $dateDebut,
            ':dateFin' => $dateFin
        ]);
        
        $result = $stmt->fetch();
        
        echo json_encode(['disponible' => ($result['count'] == 0)]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>