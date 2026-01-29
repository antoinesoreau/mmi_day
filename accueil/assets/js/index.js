let payload;
if (isLogged === 'yes') {
    payload = {
        "function": "load_connected",
        "data": ["intro", "stand"]
    };
} else {
    payload = {
        "function": "load",
        "data": ["intro", "stand"]
    };
}

fetch('controller/accueil.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
})
    .then(response => response.json())
    .then(data => {
        if (data.status_code !== "success") throw new Error("Erreur serveur");

        const intro = data.data.video_intro;
        const stands = data.data.video_stand;

        // SECTION 1 : Remplir le header (textes toujours affichés)
        document.getElementById('intro-title').textContent = intro[0];
        document.getElementById('intro-desc').textContent = intro[1];
        document.getElementById('intro-btn').textContent = intro[3];

        // 🔽🔽🔽 DÉBUT : Partie vidéo d'introduction désactivée (commentée) 🔽🔽🔽
        /*
        const videoUrl = intro[2].trim();
        const videoElement = document.querySelector('#header video');
        if (videoUrl) {
            videoElement.src = "public/" + videoUrl;
            videoElement.load();
            // videoElement.play().catch(e => console.log("Autoplay bloqué:", e));
        }
        */
        // 🔼🔼🔼 FIN : Partie vidéo d'introduction désactivée 🔼🔼🔼

        // SECTION 2 : Générer les cartes de stands
        const standsContainer = document.getElementById('stands');
        standsContainer.innerHTML = ''; // Vide le conteneur au cas où

        Object.entries(stands).forEach(([id, stand]) => {
            // Utiliser une <div> au lieu d’un <a> pour éviter les conflits
            const card = document.createElement('div');
            card.className = 'stand-card';
            card.dataset.id = id;

            // Navigation si clic en dehors du bouton Like
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.like-btn')) {
                    window.location.href = `stand?id=${id}`;
                }
            });

            const video = document.createElement('video');
            video.className = 'stand-video';
            video.src = `upload/${stand.url?.trim() || ''}`;
            video.controls = true;

            const info = document.createElement('div');
            info.className = 'stand-info';
            info.innerHTML = `
                <h3>${stand.title || ''}</h3>
                <p class="room">${stand.room || ''}</p>
                <p class="desc">${stand.description || ''}</p>
            `;

            // Bouton Like / Unlike
            const likeBtn = document.createElement('button');
            likeBtn.className = 'like-btn';
            likeBtn.textContent = stand.is_fav ? '❤️ Liked' : '🤍 Like';
            likeBtn.dataset.isFav = stand.is_fav ? '1' : '0';
            likeBtn.dataset.placeId = id;

            likeBtn.addEventListener('click', async (e) => {
                e.stopPropagation(); // Empêche la propagation (sécurité supplémentaire)

                if (isLogged !== 'yes') {
                    document.getElementById("auth-popup").style.display = 'block';
                    return;
                }

                if (!userId) {
                    alert('Erreur : identifiant utilisateur manquant.');
                    return;
                }

                const placeId = parseInt(id, 10);
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
                        if (isCurrentlyFav) {
                            likeBtn.textContent = '🤍 Like';
                            likeBtn.dataset.isFav = '0';
                        } else {
                            likeBtn.textContent = '❤️ Liked';
                            likeBtn.dataset.isFav = '1';
                        }
                    } else {
                        console.error('Erreur serveur:', result);
                        alert('Échec de la mise à jour du favori.');
                    }
                } catch (error) {
                    console.error('Erreur réseau:', error);
                    alert('Erreur de connexion. Veuillez réessayer.');
                }
            });

            card.appendChild(video);
            card.appendChild(info);
            card.appendChild(likeBtn);
            standsContainer.appendChild(card);
        });
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.body.innerHTML = "<h2>Erreur lors du chargement des données.</h2>";
    });