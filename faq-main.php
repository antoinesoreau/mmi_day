<?php
require_once "config/database.php";
require_once "functions/questionmanager.php";

$manager = new QuestionManager($dtb);
$categories = $manager->getCategories();
$questions = $manager->getPublishedQuestions();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MMI Toulon</title>
    <link rel="stylesheet" href="assets/css/faq-main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="logo-placeholder">
            MMI<br><small>TOULON</small>
        </div>
        <a href="faq-voc.html"><button class="menu-btn">Poser une question</button></a>
    </header>

    <section class="hero-section">
        <h1>FAQ</h1>
        <p class="subtitle">Toutes les réponses à vos questions sur le département MMI.</p>

        <div class="filter-wrapper">
            <div class="select-container">
                <select id="filterSelect">
                    <option value="all">Tout voir</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars(
                            $cat,
                        ) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-chevron-down select-arrow"></i>
            </div>
        </div>
    </section>

    <div class="faq-container">

        <p id="no-result-msg" style="display:none; text-align:center; padding:20px;">Aucun résultat pour cette catégorie.</p>

        <?php if (empty($questions)): ?>
            <p style="text-align:center; padding: 40px;">Aucune question publiée pour le moment.</p>
        <?php else: ?>
            <?php foreach ($questions as $q): ?>
                <div class="faq-item" data-category="<?= htmlspecialchars(
                    $q["category"],
                ) ?>">
                    <div class="faq-header">
                        <div class="header-text">
                            <span class="category-tag"><?= htmlspecialchars(
                                strtoupper($q["category"]),
                            ) ?></span>
                            <span class="faq-title"><?= htmlspecialchars(
                                $q["question"],
                            ) ?></span>
                        </div>
                        <div class="faq-icon"></div>
                    </div>
                    <div class="faq-content">
                        <div class="content-wrapper">
                            <div class="text-part">
                                <p><?= nl2br(
                                    htmlspecialchars(
                                        $q["reponse"] ??
                                            "En attente de réponse...",
                                    ),
                                ) ?></p>
                            </div>
                            <div class="image-part">
                                <div class="image-placeholder">MMI<br>TOULON</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <footer class="footer-cta">
        <h2>Une autre question ?</h2>
        <a href="faq-voc.html" class="cta-btn">C'est par ici</a>
    </footer>

    <script src="assets/js/faq-main.js"></script>
</body>
</html>
