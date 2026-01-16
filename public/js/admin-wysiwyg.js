let currentTable, currentId, mode;

function execCmd(command, value = null) {
  document.execCommand(command, false, value);
}

// Fonction pour MODIFIER un élément existant (envoie direct en BDD)
function openEditor(table, id, content, title) {
  currentTable = table;
  currentId = id;
  mode = "update";
  document.getElementById("editorTitle").textContent = title;
  document.getElementById("editor").innerHTML = content;
  document.getElementById("modal").style.display = "flex";
}

// Fonction pour l'AJOUT (remplit juste le formulaire sans envoyer en BDD)
function openEditorForAdd(targetInputId, previewId) {
  mode = "add";
  document.getElementById("editorTitle").textContent = "Rédiger la description";
  document.getElementById("editor").innerHTML = ""; // Vide au début
  document.getElementById("modal").style.display = "flex";

  // On redéfinit saveData pour qu'il ne fasse pas de fetch, mais remplisse le formulaire
  window.saveData = function () {
    const html = document.getElementById("editor").innerHTML;
    document.getElementById(targetInputId).value = html;
    document.getElementById(previewId).innerHTML = html;
    closeEditor();
  };
}

function closeEditor() {
  document.getElementById("modal").style.display = "none";
}

// La fonction de sauvegarde par défaut (pour la modification)
window.saveData = async function () {
  const html = document.getElementById("editor").innerHTML;
  const response = await fetch("admin.php?action=save_content", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ table: currentTable, id: currentId, html: html }),
  });
  if (response.ok) location.reload();
};
