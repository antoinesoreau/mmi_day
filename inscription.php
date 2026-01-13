<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription / Connexion</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<main>
    <div class="tabs">
        <button id="tabInscription" class="active">Inscription</button>
        <button id="tabConnexion">Connexion</button>
    </div>

    <div style="text-align: center; margin-top: 20px; min-height: 30px;">
        <?php if(isset($_GET['error'])): ?>
            <p style="color: red; font-weight: bold;">
                <?php 
                    if($_GET['error'] == 'login_failed') echo "Email ou mot de passe incorrect.";
                    if($_GET['error'] == 'email_exists') echo "Cet email est déjà utilisé.";
                    if($_GET['error'] == 'password_mismatch') echo "Les mots de passe ne correspondent pas.";
                ?>
            </p>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <p style="color: green; font-weight: bold;">
                <?php 
                    if($_GET['success'] == 'auto_connected') echo "Compte créé avec succès !";
                    if($_GET['success'] == 'login_ok') echo "Connexion réussie !";
                ?>
            </p>
        <?php endif; ?>
    </div>

    <h1 id="title">INSCRIPTION</h1>

    <div class="flex flex-col">
        <form id="inscription" action="controller/connexion_handler.php" method="POST">
            <div>
                <label for="nom_ins">Nom</label>
                <input type="text" id="nom_ins" name="nom" required>
            </div>
            <div>
                <label for="email_ins">Email</label>
                <input type="email" id="email_ins" name="email" required>
            </div>
            <div>
                <label for="mdp_ins">Mot de passe</label>
                <input type="password" id="mdp_ins" name="password" required>
            </div>
            <div>
                <label for="mdp_conf">Confirmation mot de passe</label>
                <input type="password" id="mdp_conf" name="password_confirm" required>
            </div>
            <div><p>Je certifie avoir lu les mentions légales</p></div>
            <div><button type="submit" name="register">Inscription</button></div>
        </form>

        <form id="connexion" action="controller/connexion_handler.php" method="POST" style="display: none;">
            <div>
                <label for="email_conn">Email</label>
                <input type="email" id="email_conn" name="email" required>
            </div>
            <div>
                <label for="mdp_conn">Mot de passe</label>
                <input type="password" id="mdp_conn" name="password" required>
            </div>
            <div><button type="submit" name="login">Connexion</button></div>
        </form>
    </div>
</main>

<script>
    const tabInscription = document.getElementById("tabInscription");
    const tabConnexion = document.getElementById("tabConnexion");
    const formInscription = document.getElementById("inscription");
    const formConnexion = document.getElementById("connexion");
    const title = document.getElementById("title");

    function showRegister() {
        formInscription.style.display = "block";
        formConnexion.style.display = "none";
        title.textContent = "INSCRIPTION";
        tabInscription.classList.add("active");
        tabConnexion.classList.remove("active");
    }

    function showLogin() {
        formInscription.style.display = "none";
        formConnexion.style.display = "block";
        title.textContent = "CONNEXION";
        tabConnexion.classList.add("active");
        tabInscription.classList.remove("active");
    }

    tabInscription.addEventListener("click", showRegister);
    tabConnexion.addEventListener("click", showLogin);

    // GESTION AUTOMATIQUE DES ONGLETS SELON L'ERREUR
    <?php if(isset($_GET['error']) && $_GET['error'] == 'login_failed'): ?>
        showLogin();
    <?php elseif(isset($_GET['error'])): ?>
        showRegister();
    <?php endif; ?>
</script>
</body>
</html>