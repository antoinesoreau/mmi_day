<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/database.php'; // fichier de connexion à la base de données
require 'functions/nb_compte.php'; // fichier pour le nombre d'inscrits
require 'functions/fonction_accueil.php'; // fichier pour le contenu d'accueil
require 'controller/accueil.php';


if (isset($dtb) && $dtb) {
    
    // NOMBRE D'INSCRITS

    function getNbCompte();

    // Texte modifiable + video modifiable + bouton modifiable et lien

    function getAccueil();

} else {
    echo "Erreur de connexion à la base de données.";
}

?>