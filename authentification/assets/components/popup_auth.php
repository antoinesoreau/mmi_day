<!-- popup_auth.php -->
<div id="auth-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:2rem; border-radius:8px; width:300px;">
    <button id="close-popup" style="float:right; background:none; border:none; font-size:1.2rem;">&times;</button>
    <h3 id="popup-title">Connexion</h3>

    <form id="auth-form">
      <input type="email" id="email" placeholder="Email" required><br><br>
      <input type="password" id="password" placeholder="Mot de passe" required><br><br>
      <button type="submit">Envoyer</button>
    </form>

    <p id="toggle-form" style="margin-top:1rem; cursor:pointer; color:blue;">
      Pas encore inscrit ? S'inscrire
    </p>
  </div>
  <script src="assets/js/auth.js"></script>
</div>