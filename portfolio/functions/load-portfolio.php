<?php

require_once __DIR__ . '/count-like.php';

/**
 * Charge les projets avec métadonnées, likes et statut favori.
 *
 * @param int $row Nombre max de projets à charger (LIMIT)
 * @param string|null $filter Pôle : 'DEVELOPPEMENT', 'CREATION', 'COMMUNICATION' ou 'NULL'
 * @param int|null $user_id ID utilisateur (null = non connecté)
 * @return array Réponse JSON-compatible
 */
function loadPortfolio($row, $filter, $user_id) {
    global $dtb;

    if (!isset($dtb)) {
        return ['status_code' => 'error', 'message' => 'BDD non initialisée'];
    }

    $row = (int) max(1, min((int)$row, 100));
    $params = [];

    $sql = "SELECT id_projet, pole, projet_titre, projet_description, projet_media_fixe FROM projet";

    if ($filter !== null && $filter !== 'NULL') {
        $sql .= " WHERE pole = ?";
        $params[] = $filter;
    }

    $sql .= " ORDER BY id_projet LIMIT " . $row; // ← pas de ?

    try {
        $stmt = $dtb->prepare($sql);
        $stmt->execute($params);
        $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($projets === false) {
            throw new Exception("Échec fetchAll");
        }

        $projects = [];
        foreach ($projets as $p) {
            $id = (int)($p['id_projet'] ?? 0);
            if ($id <= 0) continue;

            $is_fav = false;
            if ($user_id !== null) {
                $fav = $dtb->prepare("SELECT 1 FROM user_like WHERE id_user = ? AND id_projet = ? AND statut = 1");
                if ($fav && $fav->execute([$user_id, $id])) {
                    $is_fav = (bool) $fav->fetch();
                }
            }

            $number_like = 0;
            try {
                $number_like = countLike($id);
            } catch (Exception $e) {
                // ignore
            }

            $projects[$id] = [
                'pole' => $p['pole'] ?? '',
                'title' => $p['projet_titre'] ?? '',
                'url' => $p['projet_media_fixe'] ?? '',
                'description' => $p['projet_description'] ?? '',
                'is_fav' => $is_fav,
                'number_like' => $number_like
            ];
        }

        return ['status_code' => 'success', 'data' => ['projects' => $projects]];

    } catch (Throwable $e) {
        // Log détaillé
        file_put_contents(__DIR__ . '/debug.log', date('c') . ' - ' . $e->getMessage() . "\nSQL: $sql\nParams: " . json_encode($params) . "\n", FILE_APPEND);
        return ['status_code' => 'error', 'message' => 'Erreur portfolio'];
    }
}