<?php require_once "./components/admin-editor.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/admin-style.css">
    <title>Gestion des Stands</title>
</head>
<body>

<div style="background:#f4f4f4; padding:20px; margin-bottom:20px; border-radius: 8px;">
    <h3>➕ Nouveau Stand</h3>
    <form action="admin.php?action=add&type=stand" method="POST">
        <input type="text" name="nom" placeholder="Nom du Stand" required style="margin-bottom:10px; width: 250px;"><br>

        <label for="id_pole">Pôle associé :</label>
        <select name="id_pole" required>
            <option value="1">Création</option>
            <option value="2">Développement</option>
            <option value="3">Communication</option>
        </select><br><br>

        <button type="button" onclick="openEditorForAdd('hidden_desc_stand', 'preview_desc_stand')">
            📝 Rédiger la description du stand
        </button>

        <input type="hidden" name="description" id="hidden_desc_stand">

        <div id="preview_desc_stand" style="border:1px dashed #ccc; padding:10px; margin:10px 0; background:#fff; min-height: 50px;">
            Aperçu de la description vide...
        </div>

        <button type="submit" style="background:green; color:white; padding: 10px 20px; border:none; border-radius:4px; cursor:pointer;">
            Enregistrer le Stand
        </button>
    </form>
</div>

<h3>📋 Liste des Stands</h3>
<table style="width:100%; border-collapse: collapse;">
    <thead>
        <tr style="background:#eee; text-align:left;">
            <th style="padding:10px; border:1px solid #ccc;">Nom du Stand</th>
            <th style="padding:10px; border:1px solid #ccc;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $s): ?>
<tr>
    <td><?= htmlspecialchars($s["titre_stand"]) ?></td>
    <td>
        <button onclick='openEditor("stand", <?= $s[
            "id_stand"
        ] ?>, `<?= addslashes($s["description_stand"]) ?>`, "Modifier Stand")'>
            Modifier Description
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
