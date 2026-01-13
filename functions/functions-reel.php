<?php
// Récupérer les projets au hasard
function obtenirReelsAleatoires($db) {
    $stmt = $db->query("SELECT id_projet, projet_titre, projet_media_fixe FROM PROJET WHERE statut_projet = 1");
    $reels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    shuffle($reels);
    return $reels;
}

// Vérifier si l'utilisateur a liké
function userHasLiked($db, $user_id, $id_projet) {
    if (!$user_id) return false;
    $stmt = $db->prepare("SELECT id FROM `like` WHERE id_user = ? AND id_projet = ?");
    $stmt->execute([$user_id, $id_projet]);
    return (bool)$stmt->fetch();
}

// Compter les likes
function getLikeCount($db, $id_projet) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM `like` WHERE id_projet = ?");
    $stmt->execute([$id_projet]);
    return $stmt->fetchColumn();
}
?>