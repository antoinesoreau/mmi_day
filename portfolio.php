<?php session_start(); 
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
</head>
<body>

<h1>Projets du JPO MMI</h1>
<pre>
  <?php print_r($_SESSION); ?>
</pre>

<!-- Filtres -->
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

<!-- Zone d'affichage des projets -->
<div id="projects-container"></div>

<!-- Bouton "Charger plus" -->
<button id="load-more">Charger plus</button>

<!-- Injection sécurisée de l'ID utilisateur dans une variable globale JS -->
<script>
  window.APP_CONFIG = {
    userId: <?= json_encode($user_id) ?>,
    baseUrl: 'controller/project.php'
  };
</script>

<!-- Chargement du script externe -->
<script src="assets/js/portfolio.js"></script>

</body>
</html>