<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Récupération des données
    $id = $_POST["id"] ?? null;
    $reponse = $_POST["reponse"] ?? "";

    if ($id) {
        try {
            // 2. Mise à jour de la colonne 'reponse' pour la question donnée
            $sql = "UPDATE faq SET reponse = :reponse WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":reponse" => $reponse,
                ":id" => $id,
            ]);

            // 3. Redirection vers la page admin avec un succès (optionnel)
            // header("Location: admindeloic.php?status=success");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    } else {
        echo "ID manquant.";
    }
} else {
    // Si quelqu'un essaie d'accéder au fichier sans passer par le formulaire
    // header("Location: admindeloic.php");
    exit();
}
