// assets/js/portfolio.js

// Configuration depuis PHP
const BASE_URL = window.APP_CONFIG.baseUrl;
let currentRow = 4;
let currentFilter = 'NULL';
let userId = window.APP_CONFIG.userId;

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
 * Échapper le HTML pour éviter les XSS
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

/**
 * Met à jour l'affichage d'un bouton like
 */
function updateLikeButton(button, isLiked, likeCount) {
  const heart = isLiked ? '❤️' : '🤍';
  button.innerHTML = `${heart} (${likeCount})`;
  button.classList.toggle('liked', isLiked);
}

/**
 * Gérer le clic sur un bouton like
 */
function handleLikeClick(event) {
  const button = event.currentTarget;
  const projectId = parseInt(button.dataset.projectId, 10);

  // --- CORRECTION MAJEURE ICI ---
  // 1. Si l'utilisateur n'est pas connecté, on ouvre la modale et on s'arrête là.
  if (userId === null) {
    const modal = document.getElementById('auth-popup');
    if (modal) {
        modal.style.display = 'block';
    } else {
        console.error("Erreur : La modale #auth-popup est introuvable.");
    }
    return; // On stoppe la fonction, pas de requête Ajax envoyée
  }
  // -----------------------------

  const isCurrentlyLiked = button.classList.contains('liked');
  const newLikeState = !isCurrentlyLiked; // true = on va liker, false = on va annuler

  const payload = {
    function: "like_project",
    data: {
      project_id: projectId,
      like: newLikeState
    }
  };

  // Indicateur visuel de chargement
  button.disabled = true;
  const originalHTML = button.innerHTML;
  button.innerHTML = '🔄';

  fetch(BASE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
    .then(response => {
      if (!response.ok) throw new Error('Erreur réseau');
      return response.json();
    })
    .then(result => {
      if (result.status_code === 'success') {
        const newLikeCount = result.data.like_count || 0;
        updateLikeButton(button, newLikeState, newLikeCount);
      } else {
        alert('Erreur : ' + (result.message || 'Impossible de mettre à jour le like.'));
        // Restaurer l'état précédent en cas d'erreur
        updateLikeButton(button, isCurrentlyLiked, parseInt(originalHTML.match(/\d+/)[0])); 
      }
    })
    .catch(err => {
      console.error('Erreur AJAX like:', err);
      // Restaurer l'état visuel
      button.innerHTML = originalHTML;
    })
    .finally(() => {
      button.disabled = false;
    });
}

/**
 * Charger les projets et attacher les écouteurs
 */
function loadProjects(reset = true) {
  const container = document.getElementById('projects-container');
  if (reset) {
    container.innerHTML = '<p>Chargement...</p>';
    currentRow = 4;
  } else {
    currentRow += 4;
  }

  const functionName = userId !== null ? 'load_connected' : 'load_portfolio';

  const payload = {
    function: functionName,
    data: {
      row: currentRow,
      filters: currentFilter
    }
  };

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
      container.innerHTML = '';
      if (result.status_code === 'success') {
        const projects = result.data.projects;

        if (Object.keys(projects).length === 0) {
          container.innerHTML = '<p>Aucun projet trouvé.</p>';
          return;
        }

        for (const id in projects) {
          const p = projects[id];
          const isLiked = Boolean(p.is_fav);
          const likeCount = p.number_like || 0;

          const div = document.createElement('div');
          div.className = 'project';

          // Créer le bouton like
          const likeBtn = document.createElement('button');
          likeBtn.className = 'like-btn';
          likeBtn.dataset.projectId = id;

          // --- CORRECTION ICI ---
          // On attache handleLikeClick pour TOUT LE MONDE.
          // C'est handleLikeClick qui vérifiera si on est connecté ou non.
          likeBtn.addEventListener('click', handleLikeClick);
          // ----------------------

          updateLikeButton(likeBtn, isLiked, likeCount);

          div.innerHTML = `
            <h3>${escapeHtml(p.title)} <small>(${escapeHtml(p.pole)})</small></h3>
            <div class="meta">${escapeHtml(p.description)}</div>
            <div class="media-container">${renderMedia(p.url, p.title)}</div>
            <div class="actions"></div>
          `;
          
          // On insère le bouton qu'on vient de créer et configurer
          div.querySelector('.actions').appendChild(likeBtn);
          
          container.appendChild(div);
        }
      } else {
        container.innerHTML = `<p style="color:red;">Erreur : ${escapeHtml(result.message || 'Échec du chargement')}</p>`;
      }
    })
    .catch(err => {
      console.error('Erreur AJAX:', err);
      container.innerHTML = '<p style="color:red;">Erreur de connexion au serveur.</p>';
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
  
  // Gestion globale pour les autres boutons .open-auth du site (ex: dans auth.php)
  document.body.addEventListener('click', (e) => {
    if (e.target.closest('.open-auth')) {
      e.preventDefault();
      const modal = document.getElementById("auth-popup");
      if(modal) modal.style.display = 'block';
    }
  });

  document.getElementById('apply-filter').addEventListener('click', () => {
    currentFilter = document.getElementById('filter-select').value;
    loadProjects(true);
  });

  document.getElementById('load-more').addEventListener('click', () => {
    loadProjects(false);
  });

  loadProjects();
});