<?php 
// portfolio.php
session_start(); 

// --- HARMONISATION : On lit la session 'id' ---
if (isset($_SESSION['id'])) {
    $user_id = (int)$_SESSION['id'];
} else {
    $user_id = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio Projets</title>
  <link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>

<header>
    <?php include 'vue/menu.php'; ?>
</header>

<main style="padding:20px;">
    <h1>Projets du JPO MMI</h1>

    <div>
      <label for="filter-select">Filtrer par pôle :</label>
      <select id="filter-select">
        <option value="NULL">Tous les pôles</option>
        <option value="DEVELOPPEMENT">Développement</option>
        <option value="CREATION">Création</option>
        <option value="COMMUNICATION">Communication</option>
      </select>
      <button id="apply-filter">Appliquer</button>
    </div>

    <div id="projects-container"></div>
    <button id="load-more">Charger plus</button>
</main>

<script>
  window.APP_CONFIG = {
    userId: <?= json_encode($user_id) ?>,
    baseUrl: 'controller/project.php'
  };
</script>

<script src="assets/js/portfolio.js"></script>

<?php require 'assets/components/popup_auth.php'; ?>

</body>
</html>