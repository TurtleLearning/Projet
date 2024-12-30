<?php

$pageTitle = "Accueil - Le Petit Chalet dans La Montagne";

include BASE_PATH . '/includes/layout/header.php';

?>

<section class="position-relative">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item c-item active">
                <img src="../public/assets/Images/carousel/img1.jpg" class="d-block w-100 c-img" alt="Image 1">
            </div>
            <div class="carousel-item c-item">
                <img src="../public/assets/Images/carousel/img2.jpg" class="d-block w-100 c-img" alt="Image 2">
            </div>
            <div class="carousel-item c-item">
                <img src="../public/assets/Images/carousel/img3.jpg" class="d-block w-100 c-img" alt="Image 3">
            </div>
        </div>

        <!-- Indicateurs -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2"></button>
        </div>
        
        <!-- Contrôles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</section>

<!-- Nouveaux blocs -->
<section>
    <div class="row g-0">
        <!-- Bloc Activités -->
        <div class="col-12 block sec-ac" style="background-color: beige; padding: 20px;">
            <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                <img src="../public/assets/Images/layout/motoneige.jpg" alt="Réserver un séjour" style="width: 100%; height: auto;">
            </div>
            <div class="col-md-6 col-12 d-flex flex-column justify-content-center align-items-center text-center">
                <div class="description-container">
                    <a href="activites.php" class="card-hover">
                        <span>Activités</span>
                    </a>
                    <p>Découvrez les activités de saison disponibles.</p>
                </div>
            </div>
        </div>

        <!-- Bloc Réserver un séjour -->
        <div class="col-12 block sec-ac" style="background-color: gray; padding: 20px;">
            <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                <img src="../public/assets/Images/layout/lodge.jpg" alt="Réserver un séjour" style="width: 100%; height: auto;">
            </div>
            <div class="col-md-6 col-12 d-flex flex-column justify-content-center align-items-center text-center">
                <div class="description-container">
                    <a href="reservation.php" class="card-hover">
                        <span>Réservez un séjour</span>
                    </a>
                    <p>Réservez votre séjour dès maintenant pour profiter d'un moment de sérénité en altitude.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

include BASE_PATH . '/includes/components/backToTop.php';
include BASE_PATH . '/includes/components/modals.php';
include BASE_PATH . '/includes/components/cookies.php';
include BASE_PATH . '/includes/layout/footer.php';

?>