<?php
session_start();
require_once '../config/database.php'; // Remonte vers config
require_once 'functions-reel.php';   // Même dossier

// Connexion
if (isset($_POST['login'])) {
    $stmt = $dtb->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    if ($user && $_POST['password'] === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['prenom'] = $user['prenom_user'];
    }
}

// Like / Délike
if (isset($_POST['coeur']) && isset($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];
    $p_id = $_POST['id_projet'];
    
    $check = $dtb->prepare("SELECT id FROM `like` WHERE id_user = ? AND id_projet = ?");
    $check->execute([$u_id, $p_id]);
    
    if ($check->fetch()) {
        $dtb->prepare("DELETE FROM `like` WHERE id_user = ? AND id_projet = ?")->execute([$u_id, $p_id]);
    } else {
        $dtb->prepare("INSERT INTO `like` (id_user, id_projet, statut) VALUES (?, ?, 1)")->execute([$u_id, $p_id]);
    }
}

// Redirection vers la vue principale
$anchor = isset($_POST['id_projet']) ? "#projet_" . $_POST['id_projet'] : "";
header("Location: ../reels.php" . $anchor);
exit();
?>