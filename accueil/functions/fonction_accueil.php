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
        if (is_array($row)){
            $titre = $row['titre'];
            $texte = $row['description'];
            $video = $row['lien'];
            $btn = $row['btn'];
            $btn_lien = $row['btn_lien'];
        }else{
            $titre = "Bienvenue en MMI";
            $texte = "Métiers du Multimédia et de l'Internet";
            $video = "";
            $btn = "Une question ?";
            $btn_lien = "contact";
        }
        
            
        return [$titre, $texte, $video, $btn, $btn_lien];

    }

?>