<?php
function add_like($user_id, $project_id) {
    global $dtb;

    if (!is_numeric($user_id) || !is_numeric($project_id)) {
        return [
            'status_code' => 'error',
            'message' => 'ID invalide.'
        ];
    }

    $user_id = (int)$user_id;
    $project_id = (int)$project_id;

    try {
        // Vérifier si un like existe déjà (actif ou non)
        $stmt = $dtb->prepare("
            SELECT id, statut FROM user_like 
            WHERE id_user = ? AND id_projet = ? AND id_stand = 0
        ");
        $stmt->execute([$user_id, $project_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            if ((int)$existing['statut'] === 1) {
                // Déjà liké → rien à faire
                $count = countLike($project_id);
                return ['status_code' => 'success', 'data' => ['like_count' => $count]];
            } else {
                // Réactiver le like (passer statut à 1)
                $update = $dtb->prepare("UPDATE user_like SET statut = 1 WHERE id = ?");
                $update->execute([$existing['id']]);
            }
        } else {
            // Nouveau like
            $insert = $dtb->prepare("
                INSERT INTO user_like (id_projet, id_stand, id_user, statut)
                VALUES (?, 0, ?, 1)
            ");
            $insert->execute([$project_id, $user_id]);
        }

        $count = countLike($project_id);
        return ['status_code' => 'success', 'data' => ['like_count' => $count]];

    } catch (Exception $e) {
        return [
            'status_code' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}