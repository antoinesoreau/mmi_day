<?php
// On inclut le fichier de la classe
require_once "controller/admin-mainController.php";

// IMPORTANCE CRUCIALE : On crée l'instance ici
$controller = new MainController();

$action = $_GET["action"] ?? "projet";

switch ($action) {
    case "add":
        $controller->addContent();
        break;
    case "save_content":
        $controller->saveContent();
        break;
    case "projet":
        $controller->show("projet");
        break;
    case "stand":
        $controller->show("stand");
        break;
    default:
        $controller->show("faq");
        break;
}
