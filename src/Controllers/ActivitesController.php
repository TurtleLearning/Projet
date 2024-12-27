<?php
namespace App\Controllers;

class ActivitesController {
    public function index() {
        // Définir le titre de la page
        $pageTitle = "Activités - Le Petit Chalet dans La Montagne";

        // Vous pourriez ajouter ici la logique pour charger des données depuis une base de données
        // Par exemple, charger la liste des activités dynamiquement
        $activites = [
            'ete' => [
                'trekking' => [
                    'titre' => 'Trekking',
                    'description' => 'Parcourez des sentiers sauvages...',
                    'image' => '../assets/Images/activites/trekking.jpg',
                    'duree' => '2h à 5h',
                    'niveau' => 'Intermédiaire à confirmé',
                    'tarif' => 'Gratuit, accès libre'
                ],
                // ... autres activités d'été
            ],
            'hiver' => [
                'ski' => [
                    'titre' => 'Ski Alpin',
                    'description' => 'Dévalez des pistes enneigées...',
                    'image' => '../assets/Images/activites/skialpin.jpg',
                    'duree' => 'Forfait demi-journée ou journée',
                    'niveau' => 'Tous niveaux',
                    'tarif' => 'Forfait journée adulte 45€, enfant 35€'
                ],
                // ... autres activités d'hiver
            ]
        ];

        // Charger la vue
        require BASE_PATH . '/views/activites/index.php';
    }
}
