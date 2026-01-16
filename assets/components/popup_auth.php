<div id="auth-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999;">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:2rem; border-radius:8px; width:90%; max-width:400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
    
    <button id="close-popup" style="float:right; background:none; border:none; font-size:2rem; cursor:pointer; line-height:0.5;">&times;</button>
    
    <h2 id="popup-title" style="margin-top:0; text-align:center;">Connexion</h2>

    <form id="auth-form" style="display:flex; flex-direction:column; gap:15px; margin-top:20px;">
      <input type="email" id="email" placeholder="Email" required style="padding:10px; border:1px solid #ccc; border-radius:4px;">
      <input type="password" id="password" placeholder="Mot de passe" required style="padding:10px; border:1px solid #ccc; border-radius:4px;">
      <button type="submit" style="padding:12px; background:black; color:white; border:none; cursor:pointer; font-weight:bold; border-radius:4px;">VALIDER</button>
    </form>

    <div id="auth-msg" style="margin-top:10px; text-align:center; color:red;"></div>

    <p id="toggle-form" style="margin-top:1.5rem; cursor:pointer; color:blue; text-align:center; text-decoration:underline;">
      Pas encore inscrit ? S'inscrire
    </p>
  </div>
  
  <script src="assets/js/auth.js"></script>
</div>