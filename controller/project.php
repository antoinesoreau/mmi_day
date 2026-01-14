<?php
session_start();
header('Content-Type: application/json');



require_once '../config/database.php'; // Appel de la variable golbal $dtb
require '../functions/count-like.php'; // Appel fonction inscription
require '../functions/load-portfolio.php'; // Appel fonction inscription
require '../functions/add-like.php';
require '../functions/remove-like.php';


// Lecture du corps de la requête
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Validation du format JSON
if (!isset($data['function']) || !isset($data['data'])) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Données manquantes ou format invalide'
    ]);
    exit;
}

// Chargement portfolio si utilisateur non connecte

if ($data['function'] === 'load_portfolio' && isset($data['data']['row'])){
    if (isset($data['data']['filters'])){
        $filter = $data['data']['filters'];
    }else{
        $filter = null;
    }
    $row = $data['data']['row'];
    $response = loadPortfolio($row, $filter, null);
}

// Chargement portfolio si utilisateur connecte

elseif ($data['function'] === 'load_connected' && isset($data['data']['row'])){
    if (isset($data['data']['filters'])){
        $filter = $data['data']['filters'];
    }else{
        $filter = 'NULL';
    }
    $row = $data['data']['row'];
    $user_id = $_SESSION['id'];
    $response = loadPortfolio($row, $filter, $user_id);
}

// // Ajout d'un projet aux favori

elseif ($data['function'] === 'like_project') {
    if (!isset($_SESSION['id'])) {
        $response = ['status_code' => 'error', 'message' => 'Non connecté'];
    } else {
        $user_id = $_SESSION['id'];
        $project_id = (int)($data['data']['project_id'] ?? 0);
        $shouldLike = !empty($data['data']['like']); // true ou false

        if ($shouldLike) {
            $response = add_like($user_id, $project_id);
        } else {
            $response = remove_like($user_id, $project_id);
        }
    }
}

echo json_encode($response);