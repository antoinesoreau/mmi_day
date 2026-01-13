<?php
// Récupérer les projets au hasard
function obtenirReelsAleatoires($dtb) {
    $stmt = $dtb->query("SELECT id_projet, projet_titre, projet_media_fixe FROM PROJET WHERE statut_projet = 1");
    $reels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    shuffle($reels);
    return $reels;
}

// Vérifier si l'utilisateur a liké
function userHasLiked($dtb, $user_id, $id_projet) {
    if (!$user_id) return false;
    $stmt = $dtb->prepare("SELECT id FROM `like` WHERE id_user = ? AND id_projet = ?");
    $stmt->execute([$user_id, $id_projet]);
    return (bool)$stmt->fetch();
}

// Compter les likes
function getLikeCount($dtb, $id_projet) {
    $stmt = $dtb->prepare("SELECT COUNT(*) FROM `like` WHERE id_projet = ?");
    $stmt->execute([$id_projet]);
    return $stmt->fetchColumn();
}
?>