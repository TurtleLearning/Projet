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
}); 