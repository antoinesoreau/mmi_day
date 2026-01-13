<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Test Authentification</title>
  <link rel="stylesheet" href="assets/css/login.css">
  <style>
    /* Styles pour les messages d'erreur/succès dans la modale */
    .msg-container {
      margin-bottom: 15px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }
    .msg-error { color: #ff0000; } /* Rouge comme sur image_2f3dd2.png */
    .msg-success { color: #28a745; } /* Vert */
  </style>
</head>
<body>

<h1>Page de test</h1>

<button id="testBtn">Tester la pop-up</button>

<div id="authModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAuthModal()">&times;</span>

    <h2 id="modalTitle">Connexion</h2>

    <div class="msg-container">
        <?php if(isset($_GET['error'])): ?>
            <p class="msg-error">
                <?php 
                    if($_GET['error'] == 'login_failed') echo "Email ou mot de passe incorrect.";
                    if($_GET['error'] == 'email_exists') echo "Cet email est déjà utilisé.";
                    if($_GET['error'] == 'password_mismatch') echo "Les mots de passe ne correspondent pas.";
                ?>
            </p>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <p class="msg-success">
                <?php 
                    if($_GET['success'] == 'auto_connected') echo "Compte créé avec succès !";
                    if($_GET['success'] == 'login_ok') echo "Connexion réussie !";
                ?>
            </p>
        <?php endif; ?>
    </div>

    <form id="loginForm" action="controller/connexion_handler.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit" name="login">Se connecter</button>

      <p class="switch">
        Pas encore inscrit ?
        <a href="#" onclick="switchToRegister()">Créer un compte</a>
      </p>
    </form>

    <form id="registerForm" action="controller/connexion_handler.php" method="POST" style="display:none;">
      <input type="text" name="nom" placeholder="Nom" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <input type="password" name="password_confirm" placeholder="Confirmation mot de passe" required>
      <button type="submit" name="register">S'inscrire</button>

      <p class="switch">
        Déjà inscrit ?
        <a href="#" onclick="switchToLogin()">Se connecter</a>
      </p>
    </form>
  </div>
</div>

<script>
  function openAuthModal() {
    document.getElementById("authModal").style.display = "block";
  }

  function closeAuthModal() {
    document.getElementById("authModal").style.display = "none";
    // Nettoyage de l'URL pour éviter de re-afficher le message au refresh
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  function switchToRegister() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("modalTitle").innerText = "Créer un compte";
  }

  function switchToLogin() {
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("modalTitle").innerText = "Connexion";
  }

  // Action du bouton : Ouvre la pop-up sans condition (pas d'alertes)
  document.getElementById("testBtn").addEventListener("click", openAuthModal);

  // Ouverture automatique si erreur/succès dans l'URL
  <?php if(isset($_GET['error']) || isset($_GET['success'])): ?>
    openAuthModal();
    <?php if(isset($_GET['error']) && ($_GET['error'] == 'email_exists' || $_GET['error'] == 'password_mismatch')): ?>
        switchToRegister();
    <?php else: ?>
        switchToLogin();
    <?php endif; ?>
  <?php endif; ?>
</script>

</body>
</html>