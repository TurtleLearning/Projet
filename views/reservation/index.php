<?php

$pageTitle = "Réservation - Le Petit Chalet dans La Montagne";

include BASE_PATH . '/includes/layout/header.php';

?>

<main class="container" data-cookieconsent>
    <h1 class="reservation-title">Formulaire de réservation</h1>
    <div class="required-fields-notice">* : champ requis</div>

    <form action="reservation.php" method="POST">
        <div class="reservation-form">
            <div class="form-columns">
                <!-- Colonne gauche -->

                <div class="form-column">
                    <div class="personal-info-block">
                        <div class="input-group em">
                            <label class="form-label">Nom *</label>
                            <input type="text" id="nom" name="nom" class="form-control" placeholder="Veuillez entrer votre nom ici" required>
                        </div>

                        <div class="input-group em">
                            <label class="form-label">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Veuillez entrer votre prénom ici" required>
                        </div>

                        <div class="input-group em">
                            <label class="form-label">Téléphone *</label>
                            <input type="tel" id="num_tel" name="num_tel" class="form-control" placeholder="Veuillez entrer votre numéro de téléphone" required>
                        </div>

                        <div class="input-group em">
                            <label class="form-label">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Veuillez entrer votre email" required>
                        </div>
                        <div class="input-group">
                            <label class="form-label">Nombre total de personnes *</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary quantity-btn diminuer" data-action="decrease">-</button>
                                <input type="number" id="nombre_total" name="nombre_total" class="form-control" placeholder="Combien d'adultes ?" required>
                                <button type="button" class="btn btn-outline-secondary quantity-btn augmenter" data-action="increase">+</button>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">Dont nombre d'enfants (12 ans et moins)</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary quantity-btn diminuer" data-action="decrease">-</button>
                                <input type="number" id="dont_enfants" name="dont_enfants" class="form-control" placeholder="Combien d'enfants ?" required>
                                <button type="button" class="btn btn-outline-secondary quantity-btn augmenter" data-action="increase">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="conditions-container mt-4">
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date d'arrivée : * <u><i>(Après 14h impérativement)</i></u></label>
                            <div class="date-input-container">
                                <input type="text" id="date_debut" name="date_debut" class="form-control" placeholder="Sélectionnez une date d'arrivée" required readonly>
                                <button type="button" class="clear-date" data-target="date_debut">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de départ : * <u><i>(Avant 10h impérativement)</i></u></label>
                            <div class="date-input-container">
                                <input type="text" id="date_fin" name="date_fin" class="form-control" placeholder="Sélectionnez une date de départ" required readonly>
                                <button type="button" class="clear-date" data-target="date_fin">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" id="conditions" class="form-check-input" required aria-required="true">
                            <label class="form-check-label" for="conditions">J'accepte les conditions générales *</label>
                        </div>
                    </div>
                </div>


                <!-- Colonne droite -->

                <div class="form-column">
                    <div class="pricing-block">

                        <div class="input-group mb-3">
                            <label class="form-label">Total des nuits</label>
                            <div class="input-group" id="nuit">
                                <input type="number" id="quantite_nuit" name="quantite_nuit" class="form-control" placeholder="Le total de vos nuits s'affichera ici" readonly>
                                <input type="text" id="sous_total_nuit" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="form-label">Repas de midi souhaité(s) (Par adulte)</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary quantity-btn diminuer" data-action="decrease">-</button>
                                <input type="number" id="quantite_Repas_midi" name="quantite_repas_midi" class="form-control" placeholder="Combien en voulez-vous ?" min="0">
                                <button type="button" class="btn btn-outline-secondary quantity-btn augmenter" data-action="increase">+</button>
                            </div>
                            <input type="text" id="sous_total_repas_midi" class="form-control" readonly>
                        </div>

                        <div class="input-group mb-3">
                            <label class="form-label">Repas du soir souhaité(s) (Par adulte)</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary quantity-btn diminuer" data-action="decrease">-</button>
                                <input type="number" id="quantite_Repas_soir" name="quantite_repas_soir" class="form-control" placeholder="Combien en voulez-vous ?" min="0">
                                <button type="button" class="btn btn-outline-secondary quantity-btn augmenter" data-action="increase">+</button>
                            </div>
                            <input type="text" id="sous_total_repas_soir" class="form-control" readonly>
                        </div>

                        <div class="total-container">
                            <div class="mb-3">
                                <label for="sous_total" class="form-label">Sous-total HT:</label>
                                <input type="text" id="sous_total" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="total_ttc" class="form-label">Total TTC (TVA 20%):</label>
                                <input type="text" id="total_ttc" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="buttons-container">
                <button type="button" id="imprimer-btn" class="btn btn-secondary m-1">Imprimer</button>
                <button type="reset" id="reinitialiser-btn" class="btn btn-warning m-1">Réinitialiser</button>
                <button id="envoyer-btn" class="btn btn-success m-1">Valider ma réservation</button>
            </div>
        </div>
    </form>
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
</main>

<?php

include BASE_PATH . '/includes/components/backToTop.php'; echo "\n";
include BASE_PATH . '/includes/components/modals.php'; echo "\n";
include BASE_PATH . '/includes/components/cookies.php'; echo "\n";
include BASE_PATH . '/includes/layout/footer.php'; echo "\n";

?>