let payload = {
    "function": isLogged === 'yes' ? "load_connected" : "load",
    "data": {
        "id_stand": stand,
        ...(isLogged === 'yes' && { "id_user": userId })
    }
};

/**
 * Crée un élément DOM pour un média (image ou vidéo)
 * @param {Object} media - Objet avec propriétés 'nom' et 'type'
 * @returns {HTMLElement|null}
 */
function createMediaElement(media) {
    if (!media || !media.nom || !media.type) return null;

    const filePath = `upload/${media.nom.trim()}`;

    if (media.type === 'img') {
        const img = document.createElement('img');
        img.src = filePath;
        img.alt = media.nom;
        img.className = 'stand-media-item';
        img.loading = 'lazy';
        return img;
    } else if (media.type === 'video') {
        const video = document.createElement('video');
        video.src = filePath;
        video.controls = true;
        video.className = 'stand-media-item';
        return video;
    }
    return null;
}

fetch('controller/stand.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
})
.then(response => response.json())
.then(data => {
    if (data.status_code !== "success") throw new Error("Erreur serveur");

    const standData = data.data.stand; // Un seul stand

    const standsContainer = document.getElementById('stand');
    standsContainer.innerHTML = ''; // Vide le conteneur

    // Créer la carte unique
    const card = document.createElement('div');
    card.className = 'stand-card';

    // Vidéo principale
    const mainVideo = document.createElement('video');
    mainVideo.className = 'stand-video';
    mainVideo.src = `upload/${standData.url?.trim() || ''}`;
    mainVideo.controls = true;
    card.appendChild(mainVideo);

    // Informations textuelles
    const info = document.createElement('div');
    info.className = 'stand-info';
    info.innerHTML = `
        <h3>${standData.title || ''}</h3>
        <p class="room">${standData.room || ''}</p>
        <p class="desc">${standData.description || ''}</p>
    `;
    card.appendChild(info);

    // ➕ Ajout des médias additionnels si disponibles
    if (Array.isArray(standData.media_add) && standData.media_add.length > 0) {
        const mediaContainer = document.createElement('div');
        mediaContainer.className = 'stand-media-container';

        standData.media_add.forEach(media => {
            const el = createMediaElement(media);
            if (el) mediaContainer.appendChild(el);
        });

        card.appendChild(mediaContainer);
    }

    // Bouton Like / Unlike
    const likeBtn = document.createElement('button');
    likeBtn.className = 'like-btn';
    likeBtn.textContent = standData.is_fav ? '❤️ Liked' : '🤍 Like';
    likeBtn.dataset.isFav = standData.is_fav ? '1' : '0';
    likeBtn.dataset.placeId = stand;

    likeBtn.addEventListener('click', async (e) => {
        e.stopPropagation();

        if (isLogged !== 'yes') {
            document.getElementById("auth-popup").style.display = 'block';
            return;
        }

        if (!userId) {
            alert('Erreur : identifiant utilisateur manquant.');
            return;
        }

        const placeId = parseInt(stand, 10);
        const isCurrentlyFav = likeBtn.dataset.isFav === '1';
        const action = isCurrentlyFav ? 'remove_place_fav' : 'add_place_fav';

        try {
            const response = await fetch('controller/accueil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    function: action,
                    data: {
                        user_id: userId,
                        place_id: placeId
                    }
                })
            });

            const result = await response.json();

            if (result.status_code === 'success') {
                likeBtn.textContent = isCurrentlyFav ? '🤍 Like' : '❤️ Liked';
                likeBtn.dataset.isFav = isCurrentlyFav ? '0' : '1';
            } else {
                console.error('Erreur serveur:', result);
                alert('Échec de la mise à jour du favori.');
            }
        } catch (error) {
            console.error('Erreur réseau:', error);
            alert('Erreur de connexion. Veuillez réessayer.');
        }
    });

    card.appendChild(likeBtn);
    standsContainer.appendChild(card);
})
.catch(error => {
    console.error('Erreur:', error);
    document.body.innerHTML = "<h2>Erreur lors du chargement des données.</h2>";
});