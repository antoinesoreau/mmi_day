<?php
$username = 'root';
$pass = '';
$host = 'localhost';
$name = 'sc2toje9146_jpo_mmi';

// $username = 'sc2toje9146';
// $pass = 'Sae!@MmiToulon';
// $host = 'localhost';
// $name = 'sc2toje9146_jpo_mmi';


try {
    $dtb = new PDO('mysql:host='.$host.':3306;dbname='.$name.';charset=utf8mb4', $username, $pass);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Aide, pour appeler la bdd dans une fonction, faites jutse global $dtb;