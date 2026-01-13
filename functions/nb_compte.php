<?php

function getNbCompte() {
        try {
            $stmt = $dtb->query("SELECT COUNT(id) AS nbinscrit FROM user");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nbinscrit = $row['nbinscrit'];
        } catch (PDOException $e) {
            $nbinscrit = 0;
            echo "<!-- Erreur COUNT: " . htmlspecialchars($e->getMessage()) . " -->";
        }
      
        return $nbinscrit; // affichage du nombre d'inscrits

        }

?>