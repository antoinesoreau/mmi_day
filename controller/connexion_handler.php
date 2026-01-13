<?php
session_start();
require_once '../config/database.php';
require '../functions/registerUser.php'; // Fonction pour insérer un nouvel utilisateur
require '../functions/loginUser.php'; // Fonction pour authentifier un utilisateur
require '../functions/handleAuth.php'; // Fonction principale qui orchestre l’authentification ou l’inscription


// === Exécution principale ===

$referer = $_SERVER['HTTP_REFERER'] ?? '../index.php';

try {
    $response = handleAuth($_POST, $referer);

    // Mise à jour de la session **à la fin**, comme demandé
    if (isset($response['session'])) {
        $_SESSION['user_id'] = $response['session']['user_id'];
        $_SESSION['role'] = $response['session']['role'];
        $_SESSION['prenom'] = $response['session']['prenom'];
    }

    header('Location: ' . $response['redirect']);
    exit();

} catch (PDOException $e) {
    die("Erreur BDD : " . htmlspecialchars($e->getMessage()));
}