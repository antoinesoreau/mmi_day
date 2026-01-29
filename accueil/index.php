<?php
session_start();
if (isset($_SESSION['id'])) {
    $is_logged = 'yes';
} else {
    $is_logged = 'no';
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requête AJAX JSON</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <header>
        <?php include 'vue/menu.php'; ?>
        <a href="auth">inscription</a><br>
        <a href="faq">faq</a><br>
        <a href="portfolio">reels</a><br>
        <a href="config/logout">logout</a>
    </header>

    <!-- SECTION 1 : Header avec vidéo d'introduction -->
    <section id="header">
        <video autoplay muted loop playsinline>
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
        </video>
        <h1 id="intro-title"></h1>
        <p id="intro-desc"></p>
        <button id="intro-btn"></button>
    </section>

    <!-- SECTION 2 : Liste des stands -->
    <section id="stands"></section>

    
    <script>
        const isLogged = "<?php echo $is_logged; ?>";
        <?php if(isset($_SESSION['id'])){echo "const userId =". $_SESSION['id'].';';}  ?>    
    </script>
    <script src="assets/js/index.js"></script>
    <?php require 'assets/components/popup_auth.php'; ?>
    
</body>
</html>