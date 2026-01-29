<?php

/**
 * Vérifie si un utilisateur a mis un stand en favori (et que le like est actif).
 *
 * @param PDO   $pdo     Instance de connexion à la base de données
 * @param int   $idUser  ID de l'utilisateur
 * @param int   $idStand ID du stand
 * @return bool          true si favori actif, false sinon
 */
function isFavStand(int $idUser, int $idStand): bool {
    global $dtb;
    if ($idUser <= 0 || $idStand <= 0) {
        return false;
    }

    try {
        $stmt = $dtb->prepare("SELECT 1 FROM user_like WHERE id_user = ? AND id_stand = ? AND statut = 1");
        $stmt->execute([$idUser, $idStand]);
        return (bool) $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erreur dans isFavStand() : " . $e->getMessage());
        return false; // En cas d'erreur, on considère qu'il n'est pas favori (fail-safe)
    }
}