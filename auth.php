<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Authentification</title>
</head>
<body>

  <div id="auth-popup-auto-open" style="display:none;"></div>
  <button class="open-auth" >test</button>
  <button class="open-auth" >test</button>

  <?php require 'assets/components/popup_auth.php'; ?>

  
</body>
</html>