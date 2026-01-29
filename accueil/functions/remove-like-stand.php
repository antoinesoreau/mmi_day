<?php
function removeLikeStand($user_id, $stand_id) {
    global $dtb;

    if (!is_numeric($user_id) || !is_numeric($stand_id)) {
        return [
            'status_code' => 'error',
            'message' => 'ID invalide.'
        ];
    }

    $user_id = (int)$user_id;
    $stand_id = (int)$stand_id;

    try {
        // Désactiver le like (soft delete via statut = 0)
        $stmt = $dtb->prepare("
            UPDATE user_like 
            SET statut = 0 
            WHERE id_user = ? AND id_projet = 0 AND id_stand = ? AND statut = 1
        ");
        $stmt->execute([$user_id, $stand_id]);

        return ['status_code' => 'success', 'message' => 'Retire des favoris'];

    } catch (Exception $e) {
        return [
            'status_code' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}