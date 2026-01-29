<?php
function remove_like($user_id, $project_id) {
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
        // Désactiver le like (soft delete via statut = 0)
        $stmt = $dtb->prepare("
            UPDATE user_like 
            SET statut = 0 
            WHERE id_user = ? AND id_projet = ? AND id_stand = 0 AND statut = 1
        ");
        $stmt->execute([$user_id, $project_id]);

        // Compter les likes actifs restants
        $count = countLike($project_id);

        return ['status_code' => 'success', 'data' => ['like_count' => $count]];

    } catch (Exception $e) {
        return [
            'status_code' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}