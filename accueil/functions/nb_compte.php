<?php
// functions/nb_compte.php

function getNbCompte($dtb = null) {
    // 1. Récupération de la connexion (paramètre ou globale)
    if ($dtb === null) {
        global $dtb;
    }

    // Sécurité : si pas de connexion, on renvoie 0
    if (!$dtb) {
        return 0;
    }

    try {
        // 2. EXÉCUTION DE LA REQUÊTE AVEC FILTRE
        // On ne compte que les utilisateurs ayant le rôle 'visiteur'.
        // Cela exclut automatiquement les admins, stands et responsables.
        $sql = "SELECT COUNT(id) AS nbinscrit FROM user WHERE role = 'visiteur'";
        
        $stmt = $dtb->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retourne le nombre trouvé ou 0 si vide
        return $row ? (int)$row['nbinscrit'] : 0;
        
    } catch (Exception $e) {
        // En cas d'erreur (SQL ou autre), on renvoie 0 pour ne pas bloquer la page
        return 0;
    }
}
?>