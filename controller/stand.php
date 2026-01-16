<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Démarrer le tampon pour éviter les sorties accidentelles
ob_start();

// Inclusions
$includes = [
    '../config/database.php',
    '../functions/nb_compte.php',
    '../functions/add-like-stand.php',
    '../functions/remove-like-stand.php',
    '../functions/is-fav-stand.php',
    '../functions/media-row.php'
];

foreach ($includes as $file) {
    if (!file_exists($file)) {
        http_response_code(500);
        echo json_encode([
            'status_code' => 'error',
            'message' => "Fichier manquant : $file"
        ]);
        exit;
    }
    require_once $file;
}

// Étape 1 : Lecture du flux d'entrée
$rawInput = file_get_contents('php://input');
if ($rawInput === false || $rawInput === '') {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Corps de la requête vide ou illisible.'
    ]);
    exit;
}

// Étape 2 : Décodage JSON
$input = json_decode($rawInput, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Erreur de décodage JSON : ' . json_last_error_msg()
    ]);
    exit;
}

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Le corps de la requête doit être un objet JSON.'
    ]);
    exit;
}

// Étape 3 : Vérification de la clé "function"
if (!isset($input['function'])) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Champ "function" manquant dans la requête.'
    ]);
    exit;
}

$function = $input['function'];
if (!is_string($function) || trim($function) === '') {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'La valeur de "function" doit être une chaîne non vide.'
    ]);
    exit;
}

// Étape 4 : Vérification de la clé "data"
if (!isset($input['data'])) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Champ "data" manquant dans la requête.'
    ]);
    exit;
}

$data = $input['cl'] ?? $input['data']; // Correction possible si encodage corrompu (rare)
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Le champ "data" doit être un objet JSON.'
    ]);
    exit;
}

// Étape 5 : Routage sécurisé
try {
    switch ($function) {
        case 'load':
            validateAndLoadStand($data, false);
            break;

        case 'load_connected':
            validateAndLoadStand($data, true);
            break;

        case 'add_place_fav':
            validateAndToggleFavorite($data, true);
            break;

        case 'remove_place_fav':
            validateAndToggleFavorite($data, false);
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'status_code' => 'error',
                'message' => "Fonction inconnue : \"$function\". Fonctions acceptées : load, load_connected, add_place_fav, remove_place_fav."
            ]);
    }
} catch (Exception $e) {
    error_log("Erreur critique dans stand.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status_code' => 'error',
        'message' => 'Erreur interne du serveur.'
    ]);
}

ob_end_flush();
exit;


// ───────────────────────────────────────────────
// FONCTIONS DE VALIDATION ET DE TRAITEMENT
// ───────────────────────────────────────────────

function validateAndLoadStand(array $data, bool $connected): void {
    global $dtb;

    // Validation de id_stand
    if (!isset($data['id_stand'])) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Champ "id_stand" manquant dans "data".'
        ]);
        return;
    }

    $idStand = $data['id_stand'];
    if (!is_numeric($idStand) || $idStand <= 0) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'La valeur de "id_stand" doit être un entier positif.'
        ]);
        return;
    }
    $idStand = (int)$idStand;

    $idUser = null;
    if ($connected) {
        if (!isset($data['id_user'])) {
            http_response_code(400);
            echo json_encode([
                'status_code' => 'error',
                'message' => 'Champ "id_user" requis en mode connecté.'
            ]);
            return;
        }
        $idUser = $data['id_user'];
        if (!is_numeric($idUser) || $idUser <= 0) {
            http_response_code(400);
            echo json_encode([
                'status_code' => 'error',
                'message' => 'La valeur de "id_user" doit être un entier positif.'
            ]);
            return;
        }
        $idUser = (int)$idUser;
    }

    // Requête du stand
    $stmt = $dtb->prepare("
        SELECT id_stand AS id, titre_stand AS title, nom_salle AS room,
               description_stand AS description, media_fixe AS url, media_add AS media_add
        FROM stand
        WHERE id_stand = ? AND statut = 1
    ");
    $stmt->execute([$idStand]);
    $standRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$standRow) {
        http_response_code(404);
        echo json_encode([
            'status_code' => 'error',
            'message' => "Aucun stand actif trouvé avec l'ID $idStand."
        ]);
        return;
    }

    // Initialiser les flags utilisateur
    $isFav = false;
    $isVisited = false;

    if ($connected && $idUser) {
        $isFav = isFavStand($idUser, $idStand); // ✅ Utilisation de la fonction centralisée
        $isVisited = false; // À étendre plus tard si nécessaire
    }

    // Construire la réponse
    $response = [
        'status_code' => 'success',
        'data' => [
            'stand' => [
                'id' => (int)$standRow['id'],
                'title' => $standRow['title'] ?? '',
                'room' => $standRow['room'] ?? '',
                'description' => $standRow['description'] ?? '',
                'url' => $standRow['url'] ?? '',
                'pole' => '',
                'media_add' => parseMediaAdd($standRow['media_add']),
                'is_fav' => $isFav,
                'is_visited' => $isVisited
            ]
        ]
    ];

    echo json_encode($response);
}


function validateAndToggleFavorite(array $data, bool $isAdd): void {
    global $dtb;

    // Validation user_id
    if (!isset($data['user_id'])) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Champ "user_id" manquant.'
        ]);
        return;
    }
    if (!is_numeric($data['user_id']) || $data['user_id'] <= 0) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Le "user_id" doit être un entier positif.'
        ]);
        return;
    }
    $userId = (int)$data['user_id'];

    // Validation place_id
    if (!isset($data['place_id'])) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Champ "place_id" manquant.'
        ]);
        return;
    }
    if (!is_numeric($data['place_id']) || $data['place_id'] <= 0) {
        http_response_code(400);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Le "place_id" doit être un entier positif.'
        ]);
        return;
    }
    $placeId = (int)$data['place_id'];

    // Vérifier que le stand existe
    $stmt = $dtb->prepare("SELECT 1 FROM Stand WHERE id_stand = ? AND statut = 1");
    $stmt->execute([$placeId]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'status_code' => 'error',
            'message' => "Le stand avec l'ID $placeId n'existe pas ou est désactivé."
        ]);
        return;
    }

    // Vérifier que l'utilisateur existe (optionnel mais recommandé)
    $userStmt = $dtb->prepare("SELECT 1 FROM Utilisateur WHERE id_user = ?");
    $userStmt->execute([$userId]);
    if (!$userStmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'status_code' => 'error',
            'message' => "L'utilisateur avec l'ID $userId n'existe pas."
        ]);
        return;
    }

    // Appel à la fonction métier
    if ($isAdd) {
        $result = addLikeStand($userId, $placeId);
    } else {
        $result = removeLikeStand($userId, $placeId);
    }

    // Vérifier le format de la réponse des fonctions
    if (!is_array($result) || !isset($result['status_code'])) {
        error_log("Fonction add/removeLikeStand a retourné un format invalide.");
        http_response_code(500);
        echo json_encode([
            'status_code' => 'error',
            'message' => 'Erreur interne lors de la gestion du favori.'
        ]);
        return;
    }

    echo json_encode($result);
}