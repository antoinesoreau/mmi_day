<?php
// models/MenuModel.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/nb_compte.php';

class MenuModel {
    private $pdo;

    public function __construct() {
        // On récupère la nouvelle variable globale $dtb
        global $dtb; 
        $this->pdo = $dtb;
    }

    public function getUserRole($userId) {
        $stmt = $this->pdo->prepare("SELECT role FROM user WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['role'] : null;
    }

    public function getVisitorCount() {
        // On passe l'instance PDO à la fonction
        return getNbCompte($this->pdo);
    }
}
?>