document.addEventListener('DOMContentLoaded', function() {
    // Gestion des cookies
    const cookieConsent = document.getElementById('cookieConsent');
    const acceptBtn = document.getElementById('acceptCookies');
    const refuseBtn = document.getElementById('refuseCookies');

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

    // Gestion des modales du footer
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = modalOverlay.querySelector('.modal-content');
    const modalBody = modalOverlay.querySelector('.modal-body');
    const modalClose = modalOverlay.querySelector('.modal-close');

    // Contenu des différentes modales
    const modalContents = {
        'cgv': `<h2>Conditions Générales de Vente</h2>
                <p>Votre contenu CGV ici...</p>`,
        'privacy': `<h2>Politique de Confidentialité</h2>
                   <p>Votre contenu politique de confidentialité ici...</p>`,
        'cookies': `<h2>Gestion des Cookies</h2>
                   <p>Votre contenu sur les cookies ici...</p>`
    };

    // Gestionnaire pour les liens du footer
    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const type = e.target.getAttribute('data-type');
            if (modalContents[type]) {
                modalBody.innerHTML = modalContents[type];
                modalOverlay.classList.add('active');
            }
        });
    });

    // Fermeture de la modale
    modalClose.addEventListener('click', () => {
        modalOverlay.classList.remove('active');
    });

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove('active');
        }
    });
}); 