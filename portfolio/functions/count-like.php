<?php

/**
 * Compte le nombre de likes actifs (statut = 1) pour un projet donné.
 *
 * @param PDO $dtb Instance de la connexion à la base de données
 * @param int $id_projet ID du projet
 * @return int Nombre de likes
 */
function countLike(int $id_projet): int {
    global $dtb;

    try {
        $stmt = $dtb->prepare("SELECT COUNT(*) FROM user_like WHERE id_projet = ? AND statut = 1");
        $stmt->execute([$id_projet]);
        $result = $stmt->fetchColumn();
        return $result !== false ? (int) $result : 0;
    } catch (Throwable $e) {
        error_log("countLike ERROR: " . $e->getMessage());
        return 0;
    }
}