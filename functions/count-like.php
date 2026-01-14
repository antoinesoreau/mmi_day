<?php

/**
 * Compte le nombre de likes actifs (statut = 1) pour un projet donné.
 *
 * @param PDO $dtb Instance de la connexion à la base de données
 * @param int $id_projet ID du projet
 * @return int Nombre de likes
 */
function countLike(PDO $dtb, int $id_projet): int {
    $stmt = $dtb->prepare("SELECT COUNT(*) FROM user_like WHERE id_projet = ? AND statut = 1");
    $stmt->execute([$id_projet]);
    return (int) $stmt->fetchColumn();
}