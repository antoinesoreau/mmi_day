


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
        const videoUrl = intro[2].trim(); // Supprime les espaces superflus
        const videoElement = document.querySelector('#header video');
        if (videoUrl) {
            videoElement.src = "public/"+videoUrl;
            videoElement.load();
            // Optionnel : décommente la ligne suivante si tu veux aussi la jouer automatiquement
            // videoElement.play().catch(e => console.log("Autoplay bloqué:", e));
        }
        */
        // 🔼🔼🔼 FIN : Partie vidéo d'introduction désactivée 🔼🔼🔼

        // SECTION 2 : Générer les cartes de stands
const standsContainer = document.getElementById('stands');
Object.entries(stands).forEach(([id, stand]) => { // ← ICI : on récupère id + stand
    const card = document.createElement('div');
    card.className = 'stand-card';

    const video = document.createElement('video');
    video.className = 'stand-video';
    video.src = stand.url?.trim() || '';
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
    likeBtn.dataset.placeId = id; // ← utilise `id` ici

    likeBtn.addEventListener('click', async () => {
        if (isLogged !== 'yes') {
            document.getElementById("auth-popup").style.display = 'block';
            return;
        }

        // ⚠️ Assure-toi que `userId` est défini (ex: via PHP)
        // const userId = window.userId;
        if (!userId) {
            alert('Erreur : identifiant utilisateur manquant.');
            return;
        }

        const placeId = parseInt(id); // ← utilise `id` ici aussi
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
            alert('Erreur de connexion. Veuillez réessayer.'+error);
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

