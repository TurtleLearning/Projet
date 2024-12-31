// Fonction pour gérer la visibilité des indicateurs
function handleCarouselIndicators() {
    const indicators = document.querySelector('.carousel-indicators');
    if (!indicators) return;

    function updateIndicatorsVisibility() {
        if (window.innerWidth <= 1000) {
            indicators.style.display = 'none';
        } else {
            indicators.style.display = 'flex';
        }
    }

    // Vérification initiale
    updateIndicatorsVisibility();

    // Écouteur pour le redimensionnement de la fenêtre
    window.addEventListener('resize', updateIndicatorsVisibility);
}

// Exécuter quand le DOM est chargé
document.addEventListener('DOMContentLoaded', handleCarouselIndicators);