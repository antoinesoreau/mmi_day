<?php
session_start();
header('Content-Type: application/json');

require '../config/database.php'; // Connexion à la base de données
require '../functions/nb_compte.php'; // fichier pour le nombre d'inscrits
require '../functions/fonction_accueil.php'; // fichier pour le contenu d'accueil
require '../functions/add-like-stand.php';
require '../functions/remove-like-stand.php';
require '../functions/is-fav-stand.php';


// 1. On récupère le contenu brut de la requête (le flux php://input)
$json_data = file_get_contents('php://input');

// 2. On décode le JSON pour en faire un objet ou un tableau PHP
$data = json_decode($json_data, true); // "true" pour avoir un tableau associatif

// 3. On vérifie si la donnée existe et on transforme
// un objet ou un tableau PHP en JSON et on l'envoie en méthode POST en cas de succès

////////////////////////////////////////////////////////////////
// Charger les données quand l'utilisateur n'est pas connecté //
////////////////////////////////////////////////////////////////
if (isset($data['function']) && ($data['function'] == 'load')){
    $received = $data['function'];

    if (in_array('intro', $data['data']) && in_array('stand', $data['data'])) {
        $intro = 'intro';
        $stand = 'stand';

        // 1. Vérifier getAccueil()
        $video_intro = getAccueil();
        if ($video_intro === null || !is_array($video_intro)) {
            error_log("Erreur : getAccueil() a retourné une valeur invalide.");
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Impossible de charger la vidéo d'introduction."]);
            exit;
        }

        $video_stand = [];
        if (is_array($stand)) {
            foreach ($stand as $item) { // Toujours false
                if (!isset($item['id'])) continue;
                $id = (int)$item['id'];
                $video_stand[$id] = [
                    "pole" => $item['pole'] ?? "",
                    "title" => $item['title'] ?? "",
                    "room" => $item['room'] ?? "",
                    "url" => $item['url'] ?? "",
                    "description" => $item['description'] ?? "",
                ];
            }
        } else { 
            try {
                $stmt = $dtb->query("SELECT id_stand AS id, titre_stand AS title, nom_salle AS room, description_stand AS description, media_fixe AS url FROM stand WHERE statut = 1");
                $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Optionnel : log si aucun stand trouvé
                if (empty($stands)) {
                    error_log("Avertissement : Aucun stand actif trouvé dans la base de données.");
                }

                foreach ($stands as $item) {
                    $id = (int)$item['id'];
                    $video_stand[$id] = [
                        "pole" => "",
                        "title" => $item['title'] ?? "",
                        "room" => $item['room'] ?? "",
                        "url" => $item['url'] ?? "",
                        "description" => $item['description'] ?? "",
                    ];
                }
            } catch (PDOException $e) {
                error_log("Erreur PDO lors du chargement des stands : " . $e->getMessage());
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Erreur base de données: " . $e->getMessage()]);
                exit;
            }
        }

        // 2. Vérifier que video_stand n'est pas vide (si requis)
        if (empty($video_stand)) {
            error_log("Avertissement : Aucun stand n’a été chargé pour la réponse.");
            // Tu peux décider si c’est une erreur ou non. Ici, on autorise (peut-être normal).
        }

        $response = [
            "status_code" => "success",
            "data" => [
                "video_intro" => $video_intro,
                "video_stand" => $video_stand,
            ]
        ];

        // 3. Vérifier json_encode
        $json_response = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json_response === false) {
            error_log("Erreur d'encodage JSON : " . json_last_error_msg());
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erreur interne lors de la génération de la réponse."]);
            exit;
        }

        echo $json_response;
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes 3."]);
    }
}

///////////////////////////////////////////////////////////
// Charger les données quand l'utilisateur est connecté //
/////////////////////////////////////////////////////////
elseif (isset($data['function']) && ($data['function'] == 'load_connected')){
    $received = $data['function'];

    if (in_array('intro', $data['data']) && in_array('stand', $data['data'])) {
        $intro = 'intro';
        $stand = 'stand';

        $video_intro = getAccueil();
        if ($video_intro === null || !is_array($video_intro)) {
            error_log("Erreur : getAccueil() a retourné une valeur invalide (load_connected).");
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Impossible de charger la vidéo d'introduction."]);
            exit;
        }

        $video_stand = [];
        if (is_array($stand)) { // Toujours false car stand = str non array
            foreach ($stand as $item) {
                if (!isset($item['id'])) continue;
                $id = (int)$item['id'];
                $video_stand[$id] = [
                    "pole" => $item['pole'] ?? "",
                    "title" => $item['title'] ?? "",
                    "room" => $item['room'] ?? "",
                    "url" => $item['url'] ?? "",
                    "description" => $item['description'] ?? "",
                    "is_fav" => isset($item['is_fav']) ? (bool)$item['is_fav'] : false,
                    "is_visited" => isset($item['is_visited']) ? (bool)$item['is_visited'] : false,
                ];
            }
        } else {
            try {
                $stmt = $dtb->query("SELECT id_stand AS id, titre_stand AS title, nom_salle AS room, description_stand AS description, media_fixe AS url FROM stand WHERE statut = 1");
                $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($stands)) {
                    error_log("Avertissement (load_connected) : Aucun stand actif trouvé.");
                }
                if (!isset($_SESSION)){
                    error_log($_SESSION);
                }
                $user_id = $_SESSION['id'];
                foreach ($stands as $item) {
                    $id = (int)$item['id'];
                    $video_stand[$id] = [
                        "pole" => "",
                        "title" => $item['title'] ?? "",
                        "room" => $item['room'] ?? "",
                        "url" => $item['url'] ?? "",
                        "description" => $item['description'] ?? "",
                        "is_fav" => isFavstand($user_id, $id),
                        "is_visited" => false,
                    ];
                }
            } catch (PDOException $e) {
                error_log("Erreur PDO (load_connected) : " . $e->getMessage());
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Erreur base de données: " . $e->getMessage()]);
                exit;
            }
        }

        if (empty($video_stand)) {
            error_log("Avertissement (load_connected) : Aucun stand chargé.");
        }

        $response = [
            "status_code" => "success",
            "data" => [
                "video_intro" => $video_intro,
                "video_stand" => $video_stand,
            ]
        ];

        $json_response = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json_response === false) {
            error_log("Erreur d'encodage JSON (load_connected) : " . json_last_error_msg());
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erreur interne lors de la génération de la réponse."]);
            exit;
        }

        echo $json_response;
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes. 4"]);
    }
}

//////////////////////////////////////
// Route ajout d'un lieu en favori //
////////////////////////////////////
elseif (isset($data['function']) && ($data['function'] == 'add_place_fav')){
    if (isset($data['data']['user_id']) && isset($data['data']['place_id'])){
        $response = addLikestand($data['data']['user_id'], $data['data']['place_id']);
        echo json_encode($response);
    }
    else{
        http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vous tentez d'ajouter un lieu en favoris mais les données sont invalides"]);
    }
}
/////////////////////////////////////////
// Route suppression d'un lieu favori //
///////////////////////////////////////
elseif (isset($data['function']) && ($data['function'] == 'remove_place_fav')){
    if (isset($data['data']['user_id']) && isset($data['data']['place_id'])){
        $response = removeLikestand($data['data']['user_id'], $data['data']['place_id']);
        echo json_encode($response);
    }
    else{
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Vous tentez dz supprimer un lieu favoris mais les données sont invalides"]);
    }
}

else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes 1."]);
}
?>