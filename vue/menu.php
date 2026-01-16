<?php
require_once __DIR__ . '/../controller/MenuController.php';
$menuCtrl = new MenuController();
$menuInfo = $menuCtrl->getMenuData();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<header class="navbar-wrapper">

    <div class="burger-icon" id="burger-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <nav id="main-menu">
        
        <div class="menu-spacer"></div>

        <div class="menu-center">
            <a href="index.php"><img src="assets/img/logo.png" alt="Logo"></a>
        </div>

        <div class="menu-right">

            <?php if ($menuInfo['state'] === 'admin'): ?>
                <a href="<?= $menuInfo['link'] ?>" class="btn-admin">
                    <?= $menuInfo['label'] ?>
                </a>

            <?php elseif ($menuInfo['state'] === 'connected'): ?>
                
                <div class="qr-container">
                    <div id="js-qrcode"></div>
                </div>

            <?php elseif ($menuInfo['state'] === 'jour_j'): ?>
                <div class="visitor-counter">
                    <span class="count-number"><?= $menuInfo['count'] ?></span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg" style="margin-left:5px;">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>

            <?php else: ?>
                <div class="countdown-badge">
                    J-<?= $menuInfo['days'] ?> JPO
                </div>
            <?php endif; ?>

        </div>
    </nav>

</header>

<div class="menu-overlay" id="menu-overlay">
    <ul class="menu-links">
        <li><a href="programme.php">Programme</a></li>
        <li><a href="projets.php">Projets</a></li>
        <li><a href="infos.php">Infos</a></li>
        <li><a href="faq.php">FAQ</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="auth.php">Connexion / Mon compte</a></li>
    </ul>
</div>

<script>
    // --- Gestion Ouverture/Fermeture Menu ---
    function toggleMenu() {
        const burger = document.getElementById('burger-toggle');
        const overlay = document.getElementById('menu-overlay');
        const body = document.body;

        burger.classList.toggle('open');
        overlay.classList.toggle('open');

        // Bloque le scroll de la page quand le menu est ouvert
        if (overlay.classList.contains('open')) {
            body.style.overflow = 'hidden';
        } else {
            body.style.overflow = 'auto';
        }
    }

    // --- Gestion QR Code (Uniquement si connecté) ---
    <?php if ($menuInfo['state'] === 'connected'): ?>
    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("js-qrcode");
        if(container) {
            container.innerHTML = ""; 
            new QRCode(container, {
                text: "<?= $menuInfo['qr_content'] ?>",
                width: 100,
                height: 100,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.L
            });
            
            // Petit délai pour assurer l'affichage de l'image générée
            setTimeout(() => {
                let img = container.querySelector('img');
                if(img) img.style.display = 'block';
            }, 100);
        }
    });
    <?php endif; ?>
</script>