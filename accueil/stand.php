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
    <title>Stand MMI DAY</title>
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

    <section id="stand"></section>

    
    <script>
        const isLogged = "<?php echo $is_logged; ?>";
        const stand = <?php echo $_GET['id']; ?>;
        <?php if(isset($_SESSION['id'])){echo "const userId =". $_SESSION['id'].';';}  ?>    
    </script>
    <script src="assets/js/stand.js"></script>
    <?php require 'assets/components/popup_auth.php'; ?>
    
</body>
</html>