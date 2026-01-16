<?php require_once "./components/admin-editor.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/admin-style.css">
    <title>Document</title>
</head>
<body>

</body>
</html>
<div style="background:#f4f4f4; padding:20px; margin-bottom:20px;">
    <h3>➕ Nouveau Projet</h3>
    <form action="admin.php?action=add&type=projet" method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre" required>
        <select name="pole">
            <option value="CREATION">Création</option>
            <option value="DEVELOPPEMENT">Développement</option>
        </select>
        <input type="file" name="media">

        <button type="button" onclick="openEditorForAdd('hidden_desc', 'preview_desc')">📝 Rédiger la description</button>

        <input type="hidden" name="description" id="hidden_desc">
        <div id="preview_desc" style="border:1px dashed #ccc; padding:10px; margin:10px 0; background:#fff;">Aperçu vide...</div>

        <button type="submit" style="background:green; color:white;">Enregistrer le projet</button>
    </form>
</div>

<table>
    <?php foreach ($data as $p): ?>
    <tr>
        <td><?= $p["projet_titre"] ?></td>
        <td>
            <button onclick='openEditor("projet", <?= $p[
                "id_projet"
            ] ?>, `<?= addslashes(
    $p["projet_description"],
) ?>`, "Modifier Projet")'>
                Modifier Description
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php renderWysiwyg(); ?>
<script src="./public/js/admin-wysiwyg.js"></script>
