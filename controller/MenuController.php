<?php
// controller/MenuController.php
require_once __DIR__ . '/../models/MenuModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MenuController {
    
    const DATE_JPO = '2026-01-31'; 

    private $model;

    public function __construct() {
        $this->model = new MenuModel();
    }

    public function getMenuData() {
        $data = [];
        
        // Gestion de la date simulée
        if (isset($_GET['simul_date'])) {
            $today = $_GET['simul_date'];
        } else {
            $today = date('Y-m-d');
        }

        // --- HARMONISATION : On utilise les variables de session.php ---
        $userId = $_SESSION['id'] ?? null; 
        $role   = $_SESSION['role'] ?? null;

        // 1. UTILISATEUR CONNECTÉ
        if ($userId) {
            
            // Si le rôle n'est pas en session, on le récupère
            if (!$role) {
                $role = $this->model->getUserRole($userId);
            }

            if ($role === 'admin') {
                $data['state'] = 'admin';
                $data['label'] = 'DASHBOARD';
                $data['link']  = 'admin.php';
            } else {
                // Visiteur connecté : On affiche le QR Code
                $data['state']      = 'connected';
                $data['qr_content'] = $userId; 
            }
        } 
        // 2. NON CONNECTÉ
        else {
            if ($today >= self::DATE_JPO) {
                $data['state'] = 'jour_j';
                $data['count'] = $this->model->getVisitorCount();
            } else {
                $daysLeft = (strtotime(self::DATE_JPO) - strtotime($today)) / (60 * 60 * 24);
                $data['state'] = 'pre_jpo';
                $data['days']  = ceil($daysLeft);
            }
        }

        return $data;
    }
}
?>