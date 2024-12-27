<?php
$pageTitle = "404 - Page non trouvée";
include '../includes/layout/header.php';
?>

<div class="container error-page">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 text-center">
            <h1 class="display-1">404</h1>
            <h2 class="mb-4">Page non trouvée</h2>
            <p class="lead mb-5">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<?php
include '../includes/layout/footer.php';
?>
