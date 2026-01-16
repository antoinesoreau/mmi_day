<?php require_once "./components/admin-editor.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion FAQ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./public/css/admin-style.css">
</head>
<body>
    <h1>Liste des Questions (FAQ)</h1>

    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f4f4f4;">
                <th>ID</th>
                <th>Question</th>
                <th>Réponse</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $faq): ?>
            <tr>
                <td><?= $faq["id"] ?></td>
                <td><?= htmlspecialchars($faq["question"]) ?></td>
                <td><?= $faq["reponse"] ?></td>
                <td>
                    <button onclick='openEditor("faq", <?= $faq[
                        "id"
                    ] ?>, `<?= addslashes(
    $faq["reponse"],
) ?>`, "Répondre à la question")'>
                        Répondre
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php renderWysiwyg(); ?>

    <script src="./public/js/admin-wysiwyg.js"></script>
</body>
</html>
