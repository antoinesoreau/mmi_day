<?php
require_once "../config/database.php";

// Récupération des données JSON envoyées par le JS
$jsonInput = file_get_contents("php://input");
$data = json_decode($jsonInput, true);

// Vérification que la question n'est pas vide
if (!isset($data["transcription"]) || empty(trim($data["transcription"]))) {
    echo json_encode(["status" => "error", "message" => "Texte vide"]);
    exit();
}

// 1. Récupération de la catégorie (avec une valeur par défaut "Question ouverte" par sécurité)
$category =
    isset($data["category"]) && !empty($data["category"])
        ? $data["category"]
        : "Question ouverte";

try {
    // 2. Modification de la requête SQL pour inclure la colonne 'category'
    // Assure-toi d'avoir bien ajouté la colonne dans ta BDD comme indiqué précédemment
    $stmt = $dtb->prepare(
        "INSERT INTO faq (question, category, date_creation) VALUES (:texte, :categorie, NOW())",
    );

    // 3. Exécution avec les deux paramètres
    $stmt->execute([
        ":texte" => $data["transcription"],
        ":categorie" => $category,
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Enregistré avec succès !",
    ]);
} catch (PDOException $e) {
    // En cas d'erreur, on renvoie le message (utile pour debuguer si la colonne n'existe pas)
    echo json_encode([
        "status" => "error",
        "message" => "Erreur SQL : " . $e->getMessage(),
    ]);
}
?>
