<?php

$pageTitle = "Activités - Le Petit Chalet dans La Montagne";

include BASE_PATH . '/includes/layout/header.php';

?>

<!-- Activités d'Été -->
<section id="summer" class="summer-activities">
    <h2 class="text-center mb-4">Activités d'Été</h2>
    <div class="activities-container">
        <!-- Carte Trekking -->
        <article class="card h-100 card-activity" data-bs-toggle="modal" data-bs-target="#trekkingModal">
            <img src="../public/assets/Images/activites/trekking.jpg" class="card-img-top" alt="Trekking">
            <div class="card-content">
                <h3 class="card-title">Trekking</h3>
                <p class="card-text">Parcourez des sentiers sauvages et découvrez des paysages à couper le souffle, loin du tumulte quotidien.
                    Le trekking vous reconnecte à la nature, renforce votre esprit et vous offre une aventure accessible à tous niveaux.
                    Explorez forêts, montagnes et vallées en marchant à votre rythme. Chaque pas est une invitation à la liberté et à l'émerveillement.</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>

        <!-- Carte Parapente -->
        <article class="card h-100 card-activity inverted" data-bs-toggle="modal" data-bs-target="#parapenteModal">
            <img src="../public/assets/Images/activites/parapente.jpg" class="card-img-top" alt="Parapente">
            <div class="card-content">
                <h3 class="card-title">Parapente</h3>
                <p class="card-text">Survolez les sommets pour une expérience unique en toute sécurité ...</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>


        <!-- Carte VTT -->
        <article class="card h-100 card-activity" data-bs-toggle="modal" data-bs-target="#vttModal">
            <img src="../public/assets/Images/activites/vtt.jpg" class="card-img-top" alt="VTT">
            <div class="card-content">
                <h3 class="card-title">VTT</h3>
                <p class="card-text">Enfourchez votre vélo et vivez des sensations fortes sur des sentiers sinueux et des descentes exaltantes.
                    Le VTT mêle adrénaline, technicité et découverte de paysages variés. Que vous soyez amateur de balades tranquilles ou de défis sportifs,
                    il y a une piste faite pour vous. Laissez-vous surprendre par le plaisir de rouler en pleine nature !</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>
</div>
</section>

<!-- Activités d'Hiver -->
<section id="winter" class="winter-activities">
    <h2 class="text-center mb-4">Activités d'Hiver</h2>
    <div class="activities-container">
        <!-- Carte Motoneige -->
        <article class="card h-100 card-activity" data-bs-toggle="modal" data-bs-target="#motoneigeModal">
            <img src="../public/assets/Images/activites/motoneige.jpg" class="card-img-top" alt="Motoneige">
            <div class="card-content">
                <h3 class="card-title">Motoneige</h3>
                <p class="card-text">Glissez à toute vitesse sur des étendues enneigées et découvrez des panoramas hivernaux spectaculaires.
                    La motoneige vous offre une expérience unique entre adrénaline et contemplation dans des décors immaculés.
                    Parfaite pour les amateurs de sensations fortes, elle promet des souvenirs inoubliables. Équipé et prêt,
                    laissez-vous porter par le frisson de l'hiver.</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>

        <!-- Carte Ski de fond -->
        <article class="card h-100 card-activity inverted" data-bs-toggle="modal" data-bs-target="#skidefondModal">
            <img src="../public/assets/Images/activites/skidefond.jpg" class="card-img-top" alt="Ski de fond">
            <div class="card-content">
                <h3 class="card-title">Ski de fond</h3>
                <p class="card-text">Profitez de nos pistes damées pour le ski de fond...</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>


        <!-- Carte Ski Alpin -->
        <article class="card h-100 card-activity" data-bs-toggle="modal" data-bs-target="#skialpinModal">
            <img src="../public/assets/Images/activites/skialpin.jpg" class="card-img-top" alt="Ski Alpin">
            <div class="card-content">
                <h3 class="card-title">Ski Alpin</h3>
                <p class="card-text">Dévalez des pistes enneigées sous un ciel bleu éclatant et ressentez le plaisir de la glisse pure.
                    Le ski alpin est une discipline qui allie vitesse, maîtrise et plaisir, avec des paysages de montagnes grandioses pour toile de fond.
                    Débutant ou confirmé, chaque descente est une nouvelle aventure. Prêts à chausser vos skis pour une journée mémorable ?</p>
                <span class="click-info">Cliquez pour plus d'informations</span>
            </div>
        </article>
    </div>
</section>

<!-- Modals pour chaque activité -->

<!-- Modal Trekking -->
<div class="modal fade" id="trekkingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trekking en montagne</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/trekking.jpg" alt="Trekking détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Découvrez nos sentiers de randonnée à travers les Alpes...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : 2h à 5h</li>
                    <li>Niveau requis : Intermédiaire à confirmé</li>
                    <li>Équipement recommandé : Chaussures de marche, vêtements adaptés</li>
                    <li>Tarifs : Gratuit, accès libre</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les autres activités -->
<!-- Modal Parapente -->
<div class="modal fade" id="parapenteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parapente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/parapente.jpg" alt="Parapente détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Découvrez les sensations uniques du vol en parapente...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : 1h30</li>
                    <li>Niveau requis : Débutant accepté</li>
                    <li>Équipement fourni</li>
                    <li>Tarifs : À partir de 80€</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal VTT -->
<div class="modal fade" id="vttModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">VTT en montagne</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/vtt.jpg" alt="VTT détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Parcourez nos pistes adaptées à tous les niveaux, des sentiers familiaux aux descentes techniques...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : 2h à journée complète</li>
                    <li>Niveau requis : Tous niveaux</li>
                    <li>Équipement : Location possible sur place</li>
                    <li>Tarifs : Location VTT à partir de 25€/demi-journée</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Motoneige -->
<div class="modal fade" id="motoneigeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Motoneige</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/motoneige.jpg" alt="Motoneige détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Aventurez-vous sur nos circuits balisés en motoneige pour une expérience unique dans la neige...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : 1h à 3h</li>
                    <li>Niveau requis : Permis B obligatoire</li>
                    <li>Équipement : Fourni (combinaison, casque, gants)</li>
                    <li>Tarifs : À partir de 95€/personne</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ski de fond -->
<div class="modal fade" id="skidefondModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ski de fond</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/skidefond.jpg" alt="Ski de fond détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Profitez de nos pistes damées pour le ski de fond, une activité complète en pleine nature...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : À votre convenance</li>
                    <li>Niveau requis : Tous niveaux</li>
                    <li>Équipement : Location possible sur place</li>
                    <li>Tarifs : Forfait journée 15€, location matériel 20€</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ski Alpin -->
<div class="modal fade" id="skialpinModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ski Alpin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="../public/assets/Images/activites/skialpin.jpg" alt="Ski Alpin détail" class="img-fluid mb-3">
                <h4>Description détaillée</h4>
                <p>Dévalez nos pistes adaptées à tous les niveaux, des pistes vertes aux noires...</p>
                <h4>Informations pratiques</h4>
                <ul>
                    <li>Durée : Forfait demi-journée ou journée</li>
                    <li>Niveau requis : Tous niveaux</li>
                    <li>Équipement : Location possible sur place</li>
                    <li>Tarifs : Forfait journée adulte 45€, enfant 35€</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php

include BASE_PATH . '/includes/components/backToTop.php';
include BASE_PATH . '/includes/components/modals.php';
include BASE_PATH . '/includes/components/cookies.php';
include BASE_PATH . '/includes/layout/footer.php';

?>