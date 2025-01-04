// Gestion des modales
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const modalOverlay = document.getElementById('modalOverlay');
        const modalContainer = document.getElementById('modalContainer');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const closeButton = document.querySelector('.close-button');
        const backToTopButton = document.getElementById('back-to-top');
    
        // Fonction pour obtenir le titre de la modale
        function getModalTitle(type) {
            switch(type) {
                case 'cgv':
                    return 'Conditions Générales de Vente';
                case 'privacy':
                    return 'Politique de Confidentialité';
                case 'cookies':
                    return 'Gestion des Cookies';
                default:
                    return '';
            }
        }
    
        // Fonction pour obtenir le contenu de la modale
        function getModalContent(type) {
            switch(type) {
                case 'cgv':
                    return `
                        <h3>1. Application des conditions générales de vente</h3>
                        <p>Les présentes conditions générales de vente s'appliquent à toutes les réservations effectuées auprès de notre établissement.</p>
                        
                        <h3>2. Réservation</h3>
                        <p>La réservation devient effective dès lors que le client aura versé un acompte et retourné le contrat signé.</p>
                        
                        <h3>3. Annulation</h3>
                        <p>Toute annulation doit être notifiée par lettre recommandée ou email avec accusé de réception.</p>
                        
                        <h3>4. Tarifs</h3>
                        <p>Les tarifs indiqués sont en euros et TTC. Ils comprennent uniquement les prestations strictement mentionnées dans la réservation.</p>
                    `;
                case 'privacy':
                    return `
                        <h3>1. Collecte des données</h3>
                        <p>Nous collectons uniquement les données nécessaires à la gestion de votre réservation et à l'amélioration de nos services.</p>
                        
                        <h3>2. Utilisation des données</h3>
                        <p>Vos données personnelles sont utilisées uniquement dans le cadre de votre réservation et ne sont jamais partagées avec des tiers.</p>
                        
                        <h3>3. Protection des données</h3>
                        <p>Nous mettons en œuvre toutes les mesures nécessaires pour protéger vos données personnelles.</p>
                        
                        <h3>4. Vos droits</h3>
                        <p>Vous disposez d'un droit d'accès, de modification et de suppression de vos données personnelles.</p>
                    `;
                case 'cookies':
                    return `
                        <h3>1. Qu'est-ce qu'un cookie ?</h3>
                        <p>Un cookie est un petit fichier texte déposé sur votre ordinateur lors de la visite d'un site.</p>
                        
                        <h3>2. Utilisation des cookies</h3>
                        <p>Nous utilisons des cookies pour améliorer votre expérience de navigation et personnaliser nos services.</p>
                        
                        <h3>3. Types de cookies</h3>
                        <p>Nous utilisons des cookies techniques nécessaires au fonctionnement du site et des cookies analytiques pour comprendre son utilisation.</p>

                        <h3>4. Gestion des cookies</h3>
                        <p>Vous pouvez à tout moment modifier vos préférences en matière de cookies dans les paramètres de votre navigateur.</p>
                    `;
                default:
                    return '';
            }
        }

        // Fonction pour ouvrir la modale
        window.openModal = function(type) {
            modalTitle.textContent = getModalTitle(type);
            modalContent.innerHTML = getModalContent(type);
            modalOverlay.classList.add('active');
            modalContainer.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fermeture de la modale
        function closeModal() {
            modalOverlay.classList.remove('active');
            modalContainer.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Événements pour fermer la modale
        closeButton.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        // Empêcher la propagation du clic sur la modale elle-même
        modalContainer.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Gestion du bouton "Retour en haut"
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'flex';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
})();

// Gestion des cookies
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const cookieConsent = document.getElementById('cookieConsent');
        cookieConsent.setAttribute('data-cookieconsent', 'true');
        const     acceptBtn = document.getElementById('acceptCookies');
        const     refuseBtn = document.getElementById('refuseCookies');
    
        // Vérifier si l'utilisateur a déjà fait son choix
        if (!localStorage.getItem('cookieChoice')) {
            setTimeout(() => {
                cookieConsent.classList.add('active');
            }, 1000);
        }
    
        acceptBtn.addEventListener('click', () => {
            localStorage.setItem('cookieChoice', 'accepted');
            cookieConsent.classList.remove('active');
        });
    
        refuseBtn.addEventListener('click', () => {
            localStorage.setItem('cookieChoice', 'refused');
            cookieConsent.classList.remove('active');
        });
    });
})();
