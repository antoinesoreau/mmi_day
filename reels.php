<?php 
require_once 'config/config.php'; 
require_once 'functions/functions-reel.php'; 

$reels = obtenirReelsAleatoires($db);
$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interface Projets</title>
    <link rel="stylesheet" href="assets/css/style-reel.css">
</head>
<body>

    <div class="user-card">
        <?php if (!$user_id): ?>
            <form method="POST" action="functions/actions-reel.php" class="login-form">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Pass" required>
                <button name="login">Connexion</button>
            </form>
        <?php else: ?>
            <span>Bonjour <b><?= htmlspecialchars($_SESSION['prenom']) ?></b></span>
            <a href="config/logout.php" style="color:red; margin-left:10px;">Quitter</a>
        <?php endif; ?>
    </div>

    <div class="reels-container">
        <?php if (!empty($reels)): ?>
            <?php foreach ($reels as $r): ?>
                <div class="reel-block" id="projet_<?= $r['id_projet'] ?>">
                    <?php 
                    $fileName = $r['projet_media_fixe'];
                    $filePath = "assets/img/" . $fileName; 
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    ?>

                    <?php if (in_array($ext, ['mp4', 'mov', 'webm'])): ?>
                        <video src="<?= $filePath ?>" class="reel-img" autoplay loop muted playsinline></video>
                    <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                        <img src="<?= $filePath ?>" class="reel-img" alt="Projet">
                    <?php else: ?>
                        <div class="error-media">Fichier introuvable</div>
                    <?php endif; ?>

                    <div class="reel-content">
                        <h1><?= htmlspecialchars($r['projet_titre']) ?></h1>
                    </div>

                    <div class="actions-overlay">
                        <form method="POST" action="functions/actions-reel.php">
                            <input type="hidden" name="id_projet" value="<?= $r['id_projet'] ?>">
                            <input type="hidden" name="coeur" value="1">
                            <button type="button" class="btn-like-reel" 
                                    onclick="verifierConnexion(<?= $user_id ? 'true' : 'false' ?>, this.form)">
                                <?= userHasLiked($db, $user_id, $r['id_projet']) ? "❤️" : "🤍" ?>
                            </button>
                        </form>
                        <span class="compteur"><?= getLikeCount($db, $r['id_projet']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
    function verifierConnexion(estConnecte, form) {
        if (estConnecte) { form.submit(); } 
        else { alert("Veuillez vous connecter pour liker !"); }
    }
    </script>
</body>
</html>