<?php
function getConnexion() {
    $host = 'localhost'; 
    $db   = 'sc2toje9146_jpo_mmi';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die(json_encode(['status' => 'error', 'message' => 'Lien BDD échoué']));
    }
}