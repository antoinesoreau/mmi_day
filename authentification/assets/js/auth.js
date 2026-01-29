// auth.js

let isLoginMode = true;

// Ouvrir manuellement (supporte plusieurs boutons)
document.querySelectorAll('.open-auth').forEach(button => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('auth-popup').style.display = 'block';
  });
});

// Fermer
document.getElementById('close-popup')?.addEventListener('click', () => {
  document.getElementById('auth-popup').style.display = 'none';
});

// Basculer entre login / inscription
document.getElementById('toggle-form')?.addEventListener('click', () => {
  isLoginMode = !isLoginMode;
  const title = document.getElementById('popup-title');
  const toggleText = document.getElementById('toggle-form');
  if (isLoginMode) {
    title.textContent = 'Connexion';
    toggleText.textContent = "Pas encore inscrit ? S'inscrire";
  } else {
    title.textContent = 'Inscription';
    toggleText.textContent = "Déjà inscrit ? Se connecter";
  }
});

// Soumission du formulaire
document.getElementById('auth-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  const functionName = isLoginMode ? 'login' : 'signin';

  const payload = {
    function: functionName,
    data: {
      email: email,
      password: password
    }
  };

  try {
    const response = await fetch('controller/auth_rooter.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (result.status_code === 'success') {
      alert('Succès ! Bienvenue, ' + result.data.email);
      window.location.href = './';
    } else {
      alert('Erreur : ' + (result.message || 'Échec de l’opération'));
    }
  } catch (error) {
    console.error('Erreur réseau :', error);
    alert('Une erreur est survenue lors de la requête.');
  }
});

// 🔑 OUVERTURE AUTOMATIQUE SI DEMANDEE
if (document.getElementById('auth-popup-auto-open')) {
  document.getElementById('auth-popup').style.display = 'block';
  // Masquer la croix en mode forcé
  const closeBtn = document.getElementById('close-popup');
  if (closeBtn) closeBtn.style.display = 'none';
}