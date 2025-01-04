<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageType = basename($_SERVER['PHP_SELF'], '.php');

error_log("Type de page détecté : " . $pageType);

$scripts = [

    'index' => [
        "assets/js/components/carousel.js",
        "assets/js/components/modal.js",
        "assets/js/indicResponsive.js",
        "assets/js/common.js"
    ],

    'reservation' => [
        "assets/js/pages/validationReservation.js",
        "assets/js/pages/scriptReservation.js",
        "assets/js/components/modal.js",
        "assets/js/common.js"
    ],

    'contact' => [
        "assets/js/FAQ.js",
        "assets/js/pages/validationContact.js",
        "assets/js/components/modal.js",
        "assets/js/common.js"
    ],

    'activites' => [
        "assets/js/common.js"
    ]

];

if (isset($scripts[$pageType])) {
   foreach ($scripts[$pageType] as $src) {
       error_log("Chargement du script : " . $src);
       echo "<script defer src='{$src}'></script>\n";
   }
} else {
   error_log("Page type non trouvé : " . $pageType);
}
?>