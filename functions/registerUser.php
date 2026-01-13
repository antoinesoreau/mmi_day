<?php
function registerUser($email, $password, $nom) {
    global $dtb;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (email, password, role, nom, prenom_user, point_user, statut_actif, parcours_user) 
            VALUES (:email, :password, :role, :nom, :prenom, :points, :statut, :parcours)";
    
    $stmt = $dtb->prepare($sql);
    
    try {
        $stmt->execute([
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => 'visiteur',
            ':nom'      => $nom,
            ':prenom'   => 'A renseigner',
            ':points'   => 0,
            ':statut'   => 1,
            ':parcours' => 'Non défini'
        ]);
        
        return ['success' => true, 'user_id' => $dtb->lastInsertId()];
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return ['success' => false, 'error' => 'email_exists'];
        }
        throw $e; // Propage les autres erreurs
    }
}