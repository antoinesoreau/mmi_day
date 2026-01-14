<?php

require_once 'count-like.php';

/**
 * Charge les projets avec métadonnées, likes et statut favori.
 *
 * @param int $row Nombre max de projets à charger (LIMIT)
 * @param string|null $filter Pôle : 'DEVELOPPEMENT', 'CREATION', 'COMMUNICATION' ou 'NULL'
 * @param int|null $user_id ID utilisateur (null = non connecté)
 * @return array Réponse JSON-compatible
 */
function loadPortfolio(int $row, ?string $filter, ?int $user_id): array {
    global $dtb;

    // Sécurité : limiter le nombre de résultats
    $row = max(1, min($row, 100));

    // Construire la requête SQL
    $sql = "SELECT id_projet, pole, projet_titre, projet_description, projet_media_fixe FROM projet";
    if ($filter !== null && $filter !== 'NULL') {
        $sql .= " WHERE pole = ?";
    }
    $sql .= " ORDER BY id_projet LIMIT ?";

    // Préparer la requête
    $stmt = $dtb->prepare($sql);

    // Lier les paramètres avec les bons types
    if ($filter !== null && $filter !== 'NULL') {
        $stmt->bindValue(1, $filter, PDO::PARAM_STR);
        $stmt->bindValue(2, $row, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(1, $row, PDO::PARAM_INT);
    }

    // Exécuter
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Construire la réponse
    $projects = [];

    foreach ($projets as $p) {
        $id = (int) $p['id_projet'];

        // Vérifier si c'est un favori (seulement si utilisateur connecté)
        $is_fav = false;
        if ($user_id !== null) {
            $favStmt = $dtb->prepare("SELECT 1 FROM user_like WHERE id_user = ? AND id_projet = ? AND statut = 1");
            $favStmt->execute([$user_id, $id]);
            $is_fav = (bool) $favStmt->fetch();
        }

        // Compter les likes
        $number_like = countLike($dtb, $id);

        // Ajouter au tableau avec clé = id du projet
        $projects[$id] = [
            'pole' => $p['pole'],
            'title' => $p['projet_titre'],
            'url' => $p['projet_media_fixe'],
            'description' => $p['projet_description'],
            'is_fav' => $is_fav,
            'number_like' => $number_like
        ];
    }

    return [
        'status_code' => 'success',
        'data' => [
            'projects' => $projects
        ]
    ];
}