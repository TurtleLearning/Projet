document.addEventListener('DOMContentLoaded', function() {
    if (document.body.getAttribute('data-page') === 'index') {
        const carouselElement = document.getElementById('carouselExampleControls');
        if (carouselElement) {
            const carousel = new bootstrap.Carousel(carouselElement, {
                interval: 5000,
                wrap: true,
                keyboard: true
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
        }
    }
});
