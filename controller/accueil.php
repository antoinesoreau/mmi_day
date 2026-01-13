<?php
header('Content-Type: application/json');

require '../config/database.php'; // Connexion à la base de données
require 'functions/nb_compte.php'; // fichier pour le nombre d'inscrits
require 'functions/fonction_accueil.php'; // fichier pour le contenu d'accueil


// IMPORTANT : on a changé map pour stand pour que ça colle plus à la bdd

// 1. On récupère le contenu brut de la requête (le flux php://input)
$json_data = file_get_contents('php://input');

// 2. On décode le JSON pour en faire un objet ou un tableau PHP
$data = json_decode($json_data, true); // "true" pour avoir un tableau associatif

// 3. On vérifie si la donnée existe et on transforme
// un objet ou un tableau PHP en JSON et on l'envoie en méthode POST en cas de succès
if (isset($data['function']) && ($data['function'] == 'load')){
    $received = $data['function'];

    if (isset($data['intro']) && isset($data['stand'])) {
        $intro = $data['intro'];
        $stand = $data['stand'];

        $video_intro = [
            "id" => isset($intro['id']) ? (int)$intro['id'] : null,
            "url" => isset($intro['url']) ? $intro['url'] : "",
            "title" => isset($intro['title']) ? $intro['title'] : "",
            "description" => isset($intro['description']) ? $intro['description'] : "",
            "button" => isset($intro['button']) ? $intro['button'] : ""
        ];

        $video_stand = [];
        if (is_array($stand)) {
            foreach ($stand as $item) {
                if (!isset($item['id'])) continue;
                $id = (int)$item['id'];
                $video_stand[$id] = [
                    "pole" => isset($item['pole']) ? $item['pole'] : "",
                    "title" => isset($item['title']) ? $item['title'] : "",
                    "room" => isset($item['room']) ? $item['room'] : "",
                    "url" => isset($item['url']) ? $item['url'] : "",
                    "description" => isset($item['description']) ? $item['description'] : "",
                ];
            }
        } else {
            // Récupérer les stands depuis la table Stand de la BD
            try {
                $stmt = $dtb->query("SELECT id_stand AS id, titre_stand AS title, nom_salle AS room, description_stand AS description, media_fixe AS url FROM Stand WHERE statut = 1");
                $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
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
                echo json_encode(["status" => "error", "message" => "Erreur base de données: " . $e->getMessage()]);
                exit;
            }
        }

        $response = [
            "status_code" => "success",
            "data" => [
                "video_intro" => $video_intro,
                "video_stand" => $video_stand,
            ]
        ];

        echo json_encode($response);
    }

    else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes."]);
    }
}

elseif (isset($data['function']) && ($data['function'] == 'load_connected')){
    $received = $data['function'];

    if (isset($data['intro']) && isset($data['stand'])) {
        $intro = $data['intro'];
        $stand = $data['stand'];

        $video_intro = [
            "id" => isset($intro['id']) ? (int)$intro['id'] : null,
            "url" => isset($intro['url']) ? $intro['url'] : "",
            "description" => isset($intro['description']) ? $intro['description'] : "",
        ];

        $video_stand = [];
        if (is_array($stand)) {
            foreach ($stand as $item) {
                if (!isset($item['id'])) continue;
                $id = (int)$item['id'];
                $video_stand[$id] = [
                    "pole" => isset($item['pole']) ? $item['pole'] : "",
                    "title" => isset($item['title']) ? $item['title'] : "",
                    "room" => isset($item['room']) ? $item['room'] : "",
                    "url" => isset($item['url']) ? $item['url'] : "",
                    "description" => isset($item['description']) ? $item['description'] : "",
                    "is_fav" => isset($item['is_fav']) ? (bool)$item['is_fav'] : false,
                    "is_visited" => isset($item['is_visited']) ? (bool)$item['is_visited'] : false,
                ];
            }
        } else {
            // Récupérer les stands depuis la table Stand de la BD
            try {
                $stmt = $dtb->query("SELECT id_stand AS id, titre_stand AS title, nom_salle AS room, description_stand AS description, media_fixe AS url FROM Stand WHERE statut = 1");
                $stands = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($stands as $item) {
                    $id = (int)$item['id'];
                    $video_stand[$id] = [
                        "pole" => "",
                        "title" => $item['title'] ?? "",
                        "room" => $item['room'] ?? "",
                        "url" => $item['url'] ?? "",
                        "description" => $item['description'] ?? "",
                        "is_fav" => false,
                        "is_visited" => false,
                    ];
                }
            } catch (PDOException $e) {
                echo json_encode(["status" => "error", "message" => "Erreur base de données: " . $e->getMessage()]);
                exit;
            }
        }

        $response = [
            "status_code" => "success",
            "data" => [
                "video_intro" => $video_intro,
                "video_stand" => $video_stand,
            ]
        ];

        echo json_encode($response);
    }

    else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes."]);
    }

}

elseif (isset($data['function']) && ($data['function'] == 'add_place_fav')){
    $received = $data['function'];

    if (isset($data['add_place_fav'])) {
        $add_place_fav = $data['add_place_fav'];

        $response = [
            "status_code" => "success",
            "received_via" => "JSON Payload",
            "message" => "Serveur dit : " . $received,
            "add_place_fav" => $add_place_fav,
        ];

        echo json_encode($response);
    }

    else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes."]);
    }
}

else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Données JSON invalides ou manquantes."]);
}
?>