// assets/js/auth.js

// Éviter de redéclarer si le script est inclus plusieurs fois
if (typeof isLoginMode === 'undefined') {
    var isLoginMode = true;
}

document.addEventListener("DOMContentLoaded", () => {
    
    const modal = document.getElementById('auth-popup');
    const closeBtn = document.getElementById('close-popup');
    const toggleBtn = document.getElementById('toggle-form');
    const form = document.getElementById('auth-form');
    const msgBox = document.getElementById('auth-msg');

    // 1. OUVERTURE AUTOMATIQUE (Pour la page auth.php)
    if (document.getElementById('auth-popup-auto-open')) {
        if(modal) modal.style.display = 'block';
        // On cache la croix car on est sur la page dédiée
        if(closeBtn) closeBtn.style.display = 'none';
    }

    // 2. FERMETURE
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Fermeture en cliquant dehors
    window.addEventListener('click', (e) => {
        // On ne ferme pas si c'est l'ouverture auto forcée
        if (e.target === modal && !document.getElementById('auth-popup-auto-open')) {
            modal.style.display = 'none';
        }
    });

    // 3. SWITCH CONNEXION / INSCRIPTION
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            isLoginMode = !isLoginMode;
            const title = document.getElementById('popup-title');
            
            if (isLoginMode) {
                title.textContent = 'Connexion';
                toggleBtn.textContent = "Pas encore inscrit ? S'inscrire";
            } else {
                title.textContent = 'Inscription';
                toggleBtn.textContent = "Déjà inscrit ? Se connecter";
            }
            // Reset message
            if(msgBox) msgBox.textContent = "";
        });
    }

    // 4. SOUMISSION DU FORMULAIRE
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if(msgBox) msgBox.textContent = "Chargement...";
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const functionName = isLoginMode ? 'login' : 'signin';

            const payload = {
                function: functionName,
                data: { email: email, password: password }
            };

            try {
                const response = await fetch('controller/auth_rooter.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.status_code === 'success') {
                    // Succès : On recharge la page pour mettre à jour la session
                    window.location.reload(); 
                } else {
                    if(msgBox) msgBox.textContent = result.message || 'Erreur';
                }
            } catch (error) {
                console.error(error);
                if(msgBox) msgBox.textContent = "Erreur serveur";
            }
        });
    }
});