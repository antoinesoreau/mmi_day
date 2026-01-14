<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio Projets</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #fff;
    }
    h1 {
      color: #333;
    }
    .project {
      border: 1px solid #ddd;
      margin-bottom: 20px;
      padding: 16px;
      border-radius: 10px;
      background: #fafafa;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .project h3 {
      margin: 0 0 10px;
      color: #222;
    }
    .meta {
      font-size: 0.95em;
      color: #555;
      margin-bottom: 12px;
      line-height: 1.4;
    }
    .media-container {
      margin: 10px 0;
    }
    .media-container img,
    .media-container video {
      max-width: 100%;
      max-height: 200px;
      object-fit: contain;
      border-radius: 6px;
    }
    .actions button {
      margin-right: 12px;
      padding: 6px 12px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .actions button:hover {
      background: #0056b3;
    }
    select, button#apply-filter {
      padding: 6px 10px;
      margin-right: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    #load-more {
      margin-top: 20px;
      padding: 8px 16px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    #load-more:hover {
      background: #218838;
    }
  </style>
</head>
<body>

<h1>Projets du JPO MMI</h1>

<!-- Filtres -->
<div>
  <label for="filter-select">Filtrer par pôle :</label>
  <select id="filter-select">
    <option value="NULL">Tous les pôles</option>
    <option value="DEVELOPPEMENT">Développement</option>
    <option value="CREATION">Création</option>
    <option value="COMMUNICATION">Communication</option>
  </select>
  <button id="apply-filter">Appliquer</button>
</div>

<!-- Zone d'affichage des projets -->
<div id="projects-container"></div>

<!-- Bouton "Charger plus" -->
<button id="load-more">Charger plus</button>

<script>
// Configuration
const BASE_URL = 'controller/project.php'; // ← Adapte si ton contrôleur est ailleurs
let currentRow = 4;
let currentFilter = 'NULL';

// 🔑 Si tu utilises PHP pour injecter l'ID utilisateur :
// let userId = <?= isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 'null' ?>;
let userId = null; // ← Remplace par un entier si connecté (ex: 5)

/**
 * Rendu dynamique du média (image ou vidéo)
 */
function renderMedia(url, altText) {
  const fullPath = 'public/' + url;
  const lowerUrl = url.toLowerCase();

  if (
    lowerUrl.endsWith('.mp4') ||
    lowerUrl.endsWith('.webm') ||
    lowerUrl.endsWith('.ogg') ||
    lowerUrl.endsWith('.mov')
  ) {
    return `
      <video controls preload="metadata" style="max-width:100%; max-height:200px; object-fit:contain; border-radius:6px;">
        <source src="${fullPath}" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
      </video>
    `;
  } else {
    return `<img src="${fullPath}" alt="${altText}" style="max-width:100%; max-height:200px; object-fit:contain; border-radius:6px;">`;
  }
}

/**
 * Basculer favori (à implémenter côté serveur plus tard)
 */
function toggleFavorite(projectId) {
  if (userId === null) {
    alert('Veuillez vous connecter pour ajouter aux favoris.');
    return;
  }
  alert(`Fonctionnalité non encore implémentée.\nProjet ID: ${projectId}`);
}

/**
 * Charger les projets depuis l'API
 */
function loadProjects(reset = true) {
  if (reset) {
    document.getElementById('projects-container').innerHTML = '<p>Chargement...</p>';
    currentRow = 4;
  } else {
    currentRow += 4;
  }

  const functionName = userId ? 'load_connected' : 'load_portfolio';

  const payload = {
    function: functionName,
    data: {
      row: currentRow,
      filters: currentFilter
    }
  };

  if (userId !== null) {
    payload.data.user_id = userId;
  }

  fetch(BASE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(response => {
    if (!response.ok) throw new Error('Réponse réseau incorrecte');
    return response.json();
  })
  .then(result => {
    const container = document.getElementById('projects-container');

    if (result.status_code === 'success') {
      container.innerHTML = '';
      const projects = result.data.projects;

      if (Object.keys(projects).length === 0) {
        container.innerHTML = '<p>Aucun projet trouvé.</p>';
        return;
      }

      for (const id in projects) {
        const p = projects[id];
        const heart = p.is_fav ? '❤️' : '🤍';
        const likeCount = p.number_like || 0;

        const div = document.createElement('div');
        div.className = 'project';
        div.innerHTML = `
          <h3>${escapeHtml(p.title)} <small>(${escapeHtml(p.pole)})</small></h3>
          <div class="meta">${escapeHtml(p.description)}</div>
          <div class="media-container">${renderMedia(p.url, p.title)}</div>
          <div class="actions">
            <button onclick="toggleFavorite(${id})">${heart} (${likeCount})</button>
          </div>
        `;
        container.appendChild(div);
      }
    } else {
      container.innerHTML = `<p style="color:red;">Erreur : ${escapeHtml(result.message || 'Échec du chargement')}</p>`;
    }
  })
  .catch(err => {
    console.error('Erreur AJAX:', err);
    document.getElementById('projects-container').innerHTML = '<p style="color:red;">Erreur de connexion au serveur.</p>';
  });
}

/**
 * Échapper le HTML pour éviter les XSS (sécurité basique)
 */
function escapeHtml(text) {
  if (typeof text !== 'string') return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Événements
document.getElementById('apply-filter').addEventListener('click', () => {
  currentFilter = document.getElementById('filter-select').value;
  loadProjects(true);
});

document.getElementById('load-more').addEventListener('click', () => {
  loadProjects(false);
});

// Chargement initial
loadProjects();
</script>

</body>
</html>