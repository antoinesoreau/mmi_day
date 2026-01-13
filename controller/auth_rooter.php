<?php
session_start();
header('Content-Type: application/json');



require_once '../config/database.php'; // Connexion a la BDD
require '../functions/signin.php'; // Appel fonction inscription
require '../functions/login.php'; //  Appel fonction connexion
require '../functions/session.php'; //  Appel fonction session


// Lecture du corps de la requête
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Validation du format JSON
if (!isset($data['function']) || !isset($data['data']['email']) || !isset($data['data']['password'])) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Données manquantes ou format invalide'
    ]);
    exit;
}

$function = $data['function'];
$email = trim($data['data']['email']);
$password = $data['data']['password'];

// Validation basique de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Format d’email invalide'
    ]);
    exit;
}

// Mot de passe trop court ? (optionnel mais recommandé)
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Le mot de passe doit contenir au moins 6 caractères'
    ]);
    exit;
}

// Appel dynamique
switch ($function) {
    case 'login':
        $response = login($email, $password);
        break;
    case 'signin':
        $response = signin($email, $password);
        break;
    default:
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Fonction inconnue'
        ]);
        exit;
}

// Créer la session SEULEMENT en cas de succès
if ($response['status_code'] === 'success') {
    createUserSession($response['data']);
}

echo json_encode($response);