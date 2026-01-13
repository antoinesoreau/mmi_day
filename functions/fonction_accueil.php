<?php

function getAccueil() {
    
        $stmt = $dtb->query("SELECT * FROM accueil ORDER BY date_debut WHERE statut = 1 LIMIT 1"); // peut être à modifié
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $titre = $row['titre'];
        $texte = $row['description'];
        $video = $row['lien'];
        $btn = $row['btn'];
        $btn_lien = $row['btn_lien'];
            
        return $titre . $texte . $video . $btn . $btn_lien;

    }

?>