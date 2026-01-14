# site\_jpo

Le site pour la SAE 3.01 (front) et 3.03(back), qui sera le site des MMI DAY de 2026

## Architecture des dossiers

* **/assets** regroupe les fichier pour le front-end : img/vidéo fixes, js et css (dev front)
* **/config** sert aux fichiers de configuration tel que la connexion a la bdd (dev back)
* **/controler** regroupe les contrôleurs et les routes back-end qui reçoivent et renvoie les donnée (dev back)
* **/documents** est une aide avec quelques document, rien de vraiment utile pour le site (faite ce que vous voulez)
* **/functions** regroupe les fonctions back (une fonction = un fichier) (dev back)
* **/public** permet de stocker les média sauvegarder dans la bdd (dev back)


## Infos fichiers 

Les fichiers front doivent etre en .php et dans le dossier racine du projet


## Infos code
### Ajouter un popup de connexion a une action
Ajouter la class **open-auth** a l'élément (ex: bouton de connexion, like une video en non connecter...)
<div class="open-auth" ></div>

Pour forcer l'affichage du popup ajouter <div id="auth-popup-auto-open" style="display:none;"></div> sur la page

### Utilisateur connecte
if (isset($_SESSION['email']))