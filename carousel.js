document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du carousel
    const carousel = new bootstrap.Carousel(document.getElementById('carouselExampleControls'), {
        interval: 5000, // Temps entre chaque diapositive (en ms)
        wrap: true,     // Permet de revenir au début après la dernière image
        keyboard: true  // Permet la navigation avec les flèches du clavier
    });

    // Gestion des touches du clavier pour la navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
            carousel.prev();
        }
        if (event.key === 'ArrowRight') {
            carousel.next();
        }
    });
});
