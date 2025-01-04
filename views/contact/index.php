<?php

$pageTitle = "Contact - Le Petit Chalet dans La Montagne";

include BASE_PATH . '/includes/layout/header.php';

?>

<main class="container-fluid">
    
    <div class="container mt-5">
        <!-- Affichage des messages de succès/erreur -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <!-- Colonne de gauche -->
            <div class="col-md-6">
                <div class="info-block contact-form-container p-4" data-cookieconsent>
                    <h2 class="contact-title">Nous Contacter</h2>
                    <p class="avant-propos">
                        Bienvenue sur notre site !
                        <br><br>Si vous avez des interrogations concernant notre établissement, 
                        vous pouvez d'abord consulter la foire aux questions (FAQ) ci-dessous ou bien
                        nous faire part directement de votre demande par mail en remplissant 
                        au préalable le formulaire ci-dessous.
                        Nous nous efforcerons de vous répondre dans les plus brefs délais. 
                        <br><br>En vous souhaitant une agréable visite sur notre site et au plaisir de
                        vous voir bientôt dans notre établissement !
                    </p>

                    <!-- FAQ -->
                    <div class="faq-container">
                        
                        <h3>Questions Fréquentes</h3>
                        
                        <div class="faq-item">
                            <button class="faq-question">
                                Comment puis-je réserver le chalet ?
                                <span class="faq-icon">+</span>
                            </button>
                            <div class="faq-answer">
                                Vous pouvez réserver directement sur notre site dans la section "Réserver un séjour" ou nous contacter par téléphone. Nous répondons sous 24h maximum.
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                Quels sont les horaires d'arrivée et de départ ?
                                <span class="faq-icon">+</span>
                            </button>
                            <div class="faq-answer">
                                Les arrivées se font à partir de 15h et les départs avant 11h. Des arrangements sont possibles sur demande selon disponibilité.
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                Les animaux sont-ils acceptés ?
                                <span class="faq-icon">+</span>
                            </button>
                            <div class="faq-answer">
                                Oui, les animaux de compagnie sont les bienvenus avec un supplément de 15€ par jour. Merci de nous prévenir à l'avance.
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                Y a-t-il un accès WiFi ?
                                <span class="faq-icon">+</span>
                            </button>
                            <div class="faq-answer">
                                Oui, une connexion WiFi gratuite et illimitée est disponible dans tout le chalet. Le code d'accès vous sera fourni à votre arrivée.
                            </div>
                        </div>
                        
                    </div>

                    <!-- Formulaire de contact -->
                    <form id="formContact" action="contact.php" method="POST">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   placeholder="Jean Dupont" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact" class="form-label">Email ou Téléphone</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   placeholder="xxxx@xxx.xxx ou 06.00.00.00.00" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="thematique" class="form-label">Thématique</label>
                            <select class="form-select" id="thematique" name="thematique" required>
                                <option value="reservation">Réservation</option>
                                <option value="activites">Activités</option>
                                <option value="divers">Divers</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" 
                                      placeholder="Décrivez votre demande ici..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>

            <!-- Colonne de droite - Informations -->
            <div class="col-md-6">
                <!-- Bloc 1 - Coordonnées -->
                <div class="info-block ma-top mb-2 b-2 p-4">
                    <h3>Nos Coordonnées</h3>
                    <p><i class="bi bi-telephone"></i> 00.01.02.03.04</p>
                    <p><i class="bi bi-envelope"></i> lepetitchaletdanslamontagne@outlook.fr</p>
                </div>

                <!-- Bloc 2 - Accès -->
                <div class="info-block mb-2 p-4 ">
                    <h3>Comment nous trouver</h3>
                    <h4 class="ete">En été</h4>
                    <p>Accès facile par la route départementale D123. Parking disponible devant le chalet.</p>
                    
                    <h4 class="hiver">En hiver</h4>
                    <p>Accès possible uniquement avec véhicule équipé de chaînes ou pneus neige. 
                    Service de navette disponible depuis le village en cas de fortes chutes de neige.</p>

                    <h4 id="handicap">Handicap / Personnes à mobilité réduite</h4>
                    <p>Nous attachons une grande importance à aider, et disposons de tous les moyens nécessaires capables d'accompagner,
                    les personnes dont les capacités de mobilités et de prise en charge autonome sont réduites. De ce fait, si vous souhaitez vous rendre
                    dans notre établissement, veuillez nous appeler directement au préalable, grâce au numéro de l'établissement disponible dans la rubrique "Nos coordonnées"
                    afin de convenir ensemble de la meilleure manière de vous accueillir.
                    <BR></BR>
                    Du matériel adapté et une rampe d'accès sont disponibles sur place.</p>
                    
                    <p><u><strong>Adresse :</strong></u><br>
                    Le Petit Chalet dans La Montagne<br>
                    123 Route des Sapins<br>
                    74000 La Montagne<br>
                    France</p>
                </div>
                
                <!-- Bloc 3 - Carte -->
                <div class="info-block mb-4 p-4">
                    <h3>Notre Localisation</h3>
                    <div id="map" style="height: 500px; width: 100%;">
                        <iframe data-cookieconsent="marketing"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5559.865445205138!2d6.860025050830403!3d45.83262915972968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4789459fb534be51%3A0xa908728c5dcec4c0!2sMont%20Blanc!5e0!3m2!1sfr!2sfr!4v1735654393844!5m2!1sfr!2sfr"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            loading="lazy"
                            referrerpolicy="no-referrer"
                            allowfullscreen
                            sandbox="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                            importance="low"
                        ></iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php

include BASE_PATH . '/includes/components/backToTop.php'; echo "\n";
include BASE_PATH . '/includes/components/modals.php'; echo "\n";
include BASE_PATH . '/includes/components/cookies.php'; echo "\n";
include BASE_PATH . '/includes/layout/footer.php'; echo "\n";

?>