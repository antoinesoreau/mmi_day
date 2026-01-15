<?php

function getAccueil() {
        global $dtb;
        $stmt = $dtb->query("SELECT * 
            FROM accueil 
            WHERE statut = 1 
            AND date_debut <= CURDATE() 
            ORDER BY date_debut DESC 
            LIMIT 1
            ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $titre = $row['titre'];
        $texte = $row['description'];
        $video = $row['lien'];
        $btn = $row['btn'];
        $btn_lien = $row['btn_lien'];
            
        return [$titre, $texte, $video, $btn, $btn_lien];

    }

?>