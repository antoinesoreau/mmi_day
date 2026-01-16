<?php
// config/database.php

$host = 'localhost';
$dbname = 'sc2toje9146_jpo_mmi';
$user = 'root';
$pass = '';

// echo "connexion réussi";

try {
    // Création de la variable globale $dtb utilisée dans les modèles
    $dtb = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $dtb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dtb->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    // IMPORTANT : Ne rien afficher ici (pas de echo) pour ne pas casser le JSON du contrôleur
} catch (PDOException $e) {
    // En cas d'erreur critique, on arrête tout proprement
    die(json_encode(["status_code" => "error", "error_type" => "Erreur connexion BDD: " . $e->getMessage()]));
}
?>