<?php
// auth.php
session_start();

// Si déjà connecté, on redirige vers l'accueil
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Authentification</title>
  <link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>

  <header>
      <?php include 'vue/menu.php'; ?>
  </header>

  <div id="auth-popup-auto-open" style="display:none;"></div>

  <?php require 'assets/components/popup_auth.php'; ?>
  
</body>
</html>