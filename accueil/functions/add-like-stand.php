<?php 
function addLikeStand($user_id, $stand_id) {
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
        // Vérifier si un like existe déjà
        $stmt = $dtb->prepare("
            SELECT id, statut FROM user_like 
            WHERE id_user = ? AND id_projet = 0 AND id_stand = ?
        ");
        $stmt->execute([$user_id, $stand_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            if ((int)$existing['statut'] === 1) {
                // Déjà liké → rien à faire
                return ['status_code' => 'success', 'message' => 'Déjà en favori'];
            } else {
                // Réactiver le like
                $update = $dtb->prepare("UPDATE user_like SET statut = 1 WHERE id = ?");
                $update->execute([$existing['id']]);
                // 🔴 Manquait ici :
                return ['status_code' => 'success', 'message' => 'Ajouté aux favoris'];
            }
        } else {
            // Nouveau like
            $insert = $dtb->prepare("
                INSERT INTO user_like (id_projet, id_stand, id_user, statut)
                VALUES (0, ?, ?, 1)
            ");
            $insert->execute([$stand_id, $user_id]);
            return ['status_code' => 'success', 'message' => 'Ajouté aux favoris'];
        }

    } catch (Exception $e) {
        return [
            'status_code' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}