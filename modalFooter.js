function openModal(type) {
    // Contenu des modales
    const contents = {
        'cgv': {
            title: 'Conditions Générales de Vente',
            content: `
                <div class="modal-article">
                    <h3>Article 1 : Objet et champ d'application</h3>
                    <p>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre la société <i><b>Le Petit Chalet dans La Montagne</b></i>,
                    ci-après dénommée "le Prestataire", et toute personne physique ou morale effectuant une réservation sur le site <i><b>lepetitchaletdanslamontagne.fr</b></i>,
                    ci-après dénommée "le Client".</p>

                    <h3>Article 2 : Réservations et tarifs</h3>
                    <div class="modal-section">
                        <h4>2.1 Réservation</h4>
                        <ul>
                            <li>La réservation devient effective après confirmation par le Prestataire et règlement de l'acompte par le Client</li>
                            <li>Un email de confirmation sera envoyé au Client</li>
                            <li>Les tarifs sont indiqués en euros TTC</li>
                            <li>Des suppléments peuvent s'appliquer pour les animaux de compagnie</li>
                        </ul>

                        <h4>2.2 Conditions spécifiques</h4>
                        <ul>
                            <li>Enfants : L'âge des enfants doit être précisé lors de la réservation</li>
                            <li>Animaux de compagnie : Seuls les chiens et chats sont acceptés, sous conditions</li>
                            <li>Accessibilité : Les logements adaptés aux PMR doivent faire l'objet d'une demande spécifique</li>
                        </ul>
                    </div>
                </div>`
        },
        'privacy': {
            title: 'Politique de Confidentialité',
            content: `
                <div class="modal-article">
                    <h3>Protection de vos données</h3>
                    <p>Nous accordons une importance particulière à la protection de vos données personnelles.</p>

                    <div class="modal-section">
                        <h4>1. Collecte des données personnelles</h4>
                        <ul>
                            <li>Nom et prénom</li>
                            <li>Adresse email</li>
                            <li>Numéro de téléphone</li>
                            <li>Adresse postale</li>
                            <li>Informations de paiement</li>
                            <li>Données relatives aux accompagnants (dont enfants)</li>
                            <li>Informations concernant les animaux de compagnie</li>
                            <li>Besoins spécifiques liés à l'accessibilité</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>2. Utilisation des données</h4>
                        <p>Vos données sont utilisées pour :</p>
                        <ul>
                            <li>Gérer votre réservation</li>
                            <li>Vous contacter concernant votre séjour</li>
                            <li>Personnaliser votre accueil</li>
                            <li>Respecter nos obligations légales</li>
                            <li>Améliorer nos services</li>
                            <li>Vous envoyer des offres commerciales (avec votre accord)</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>3. Protection des données</h4>
                        <p>Nous nous engageons à :</p>
                        <ul>
                            <li>Sécuriser vos données</li>
                            <li>Ne pas les vendre à des tiers</li>
                            <li>Les conserver uniquement pendant la durée nécessaire</li>
                            <li>Respecter le RGPD</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>4. Droits des utilisateurs</h4>
                        <p>Vous disposez des droits suivants :</p>
                        <ul>
                            <li>Accès à vos données</li>
                            <li>Rectification</li>
                            <li>Effacement</li>
                            <li>Opposition au traitement</li>
                            <li>Portabilité des données</li>
                            <li>Retrait du consentement</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>5. Contact DPO</h4>
                        <p>Pour exercer vos droits : [email du DPO]</p>
                    </div>
                </div>`
        },
        'cookies': {
            title: 'Gestion des Cookies',
            content: `
                <div class="modal-article">
                    <h3>Notre politique en matière de cookies</h3>
                    <p>Les cookies nous permettent d'améliorer votre expérience sur notre site.</p>

                    <div class="modal-section">
                        <h4>1. Définition</h4>
                        <p>Les cookies sont des fichiers texte stockés sur votre navigateur permettant de :</p>
                        <ul>
                            <li>Mémoriser vos préférences</li>
                            <li>Analyser votre navigation</li>
                            <li>Personnaliser votre expérience</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>2. Types de cookies utilisés</h4>
                        <h5>2.1 Cookies essentiels</h5>
                        <ul>
                            <li>Gestion du panier</li>
                            <li>Authentification</li>
                            <li>Sécurité</li>
                        </ul>
                        <h5>2.2 Cookies analytiques</h5>
                        <ul>
                            <li>Mesure d'audience</li>
                            <li>Amélioration du site</li>
                        </ul>
                        <h5>2.3 Cookies marketing (optionnels)</h5>
                        <ul>
                            <li>Personnalisation des offres</li>
                            <li>Publicités ciblées</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>3. Gestion des cookies</h4>
                        <p>Vous pouvez :</p>
                        <ul>
                            <li>Accepter/refuser les cookies via notre bandeau</li>
                            <li>Modifier vos préférences à tout moment</li>
                            <li>Supprimer les cookies via votre navigateur</li>
                        </ul>
                    </div>

                    <div class="modal-section">
                        <h4>4. Durée de conservation</h4>
                        <p>Cookies de session : supprimés à la fermeture du navigateur</p>
                        <p>Cookies persistants : maximum 13 mois</p>
                    </div>

                    <div class="modal-section">
                        <h4>5. Impact du refus des cookies</h4>
                        <p>Le refus des cookies peut :</p>
                        <ul>
                            <li>Limiter certaines fonctionnalités</li>
                            <li>Empêcher la personnalisation</li>
                            <li>Affecter l'expérience utilisateur</li>
                        </ul>
                    </div>

                    <p>Ces documents sont mis à jour régulièrement pour respecter la législation en vigueur.</p>
                </div>`
        }
    };

    // Création de la carte modale
    const modal = document.createElement('div');
    modal.className = 'modal-card';
    modal.innerHTML = `
        <div class="modal-header">
            <h2>${contents[type].title}</h2>
            <button onclick="closeModal(this)" class="close-button">&times;</button>
        </div>
        <div class="modal-content">
            ${contents[type].content}
        </div>
    `;

    document.body.appendChild(modal);
    
    // Animation d'entrée
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function closeModal(button) {
    const modal = button.closest('.modal-card');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.remove();
    }, 300); // Correspond à la durée de la transition
} 