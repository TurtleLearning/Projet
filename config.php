<?php
// config.php
$host = 'sqlprive-pc2372-001.eu.clouddb.ovh.net:35167'; // ou l'adresse de votre serveur
$db = 'cefiidev1410';
$user = 'cefiidev1410';
$pass = '8YTq84Xay';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
