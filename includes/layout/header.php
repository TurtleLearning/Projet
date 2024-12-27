<!DOCTYPE html>

<html lang="fr">
    
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Le Petit Chalet dans La Montagne'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../public/assets/Images/favicon.png">

    <!-- CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    <!-- JS -->

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script defer src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

    <script defer src="assets/js/common.js"></script>
    <script defer src="assets/js/components/modal.js"></script>
    <script defer src="assets/js/components/carousel.js"></script>
    <script defer src="assets/js/FAQ.js"></script>
    <script defer src="assets/js/pages/scriptReservation.js"></script>


</head>

<body data-page="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">

    <header class="header-container">
        <nav class="navbar navbar-expand-xxl navbar-light">
            <div class="container-fluid">
                <!-- Logo et titre groupés -->
                <div class="brand-group">
                    <a class="navbar-brand p-0 m-0" href="index.php">
                        <img src="../public/assets/Images/cabin-logo.png" alt="Logo" height="60">
                    </a>
                    <h1 class="main-title">Le Petit Chalet dans La Montagne</h1>
                </div>
                
                <!-- Menu burger pour mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Menu de navigation -->
                <div class="collapse navbar-collapse justify-content-end" style="gap:2em" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="activites.php">
                                Les Activités
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="activites.php#summer">Été</a></li>
                                <li><a class="dropdown-item" href="activites.php#winter">Hiver</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reservation.php">Réserver un séjour</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Nous Contacter</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>