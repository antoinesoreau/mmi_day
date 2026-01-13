<?php
require_once "../config/database.php";
require_once "../functions/questionmanager.php";

header("Content-Type: application/json"); // Important pour le retour JS

$jsonInput = file_get_contents("php://input");
$data = json_decode($jsonInput, true);

if (!isset($data["transcription"]) || empty(trim($data["transcription"]))) {
    echo json_encode(["status" => "error", "message" => "Texte vide"]);
    exit();
}

try {
    $manager = new QuestionManager($dtb);
    $category = $data["category"] ?? "Question ouverte";

    // Ajout de la question
    $manager->addQuestion($data["transcription"], $category);

    echo json_encode([
        "status" => "success",
        "message" => "Question reçue ! Elle sera publiée après validation.",
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur : " . $e->getMessage(),
    ]);
}
