<?php
session_start();
require_once '../../config/database.php';

// Déterminer la page d'origine pour la redirection en cas d'erreur
$referer = $_SERVER['HTTP_REFERER'] ?? '../../index.php';
// On nettoie l'URL du referer pour éviter de cumuler les paramètres d'erreur
$redirect_url = strtok($referer, '?');

if (isset($_POST['register'])) {
    // Récupération du nouveau champ NOM
    $nom = trim($_POST['nom'] ?? 'Non renseigné'); 
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Vérification des mots de passe
    if ($password !== $password_confirm) {
        header("Location: $redirect_url?error=password_mismatch");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparation de l'insertion avec le champ NOM
    $sql = "INSERT INTO user (email, password, role, nom, prenom_user, point_user, statut_actif, parcours_user) 
            VALUES (:email, :password, :role, :nom, :prenom, :points, :statut, :parcours)";
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => 'visiteur',
            ':nom'      => $nom, // On utilise ici la variable $nom récupérée du POST
            ':prenom'   => 'A renseigner', 
            ':points'   => 0,
            ':statut'   => 1,
            ':parcours' => 'Non défini'
        ]);
        
        // --- CONNEXION AUTOMATIQUE ---
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['role'] = 'visiteur';
        $_SESSION['prenom'] = 'A renseigner';

        // Redirection vers l'accueil ou la page d'origine en cas de succès
        header('Location: ../../index.php?success=auto_connected');
        exit();
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Email déjà existant
            header("Location: $redirect_url?error=email_exists");
        } else {
            die("Erreur BDD : " . $e->getMessage());
        }
    }
    exit();
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['prenom'] = $user['prenom_user'];
        
        // Redirection vers index.php (racine)
        header('Location: ../../index.php?success=login_ok');
    } else {
        // En cas d'erreur de login, on renvoie sur la page d'origine
        header("Location: $redirect_url?error=login_failed");
    }
    exit();
}