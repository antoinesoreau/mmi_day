<?php
/**
 * Crée une session utilisateur sécurisée à partir des données fournies.
 *
 * @param int    $id       ID de l'utilisateur
 * @param string $email    Email de l'utilisateur
 * @param string $role     Rôle de l'utilisateur (ex: 'visiteur', 'admin', etc.)
 * @return void
 */
function createUserSession($user){
    // Régénérer l'ID de session pour prévenir le fixation d'ID
    session_regenerate_id(true);

    // Stocker les données utiles en session
    $_SESSION['nom'] = $user['nom'] ?? null;
    $_SESSION['id'] = $user['id'];
    $_SESSION['prenom'] = $user['prenom'] ?? null;
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

}