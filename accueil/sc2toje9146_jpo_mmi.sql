-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 24 jan. 2026 à 09:30
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sc2toje9146_jpo_mmi`
--

-- --------------------------------------------------------

--
-- Structure de la table `accueil`
--

CREATE TABLE `accueil` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `btn` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL,
  `btn_lien` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `accueil`
--

INSERT INTO `accueil` (`id`, `titre`, `description`, `btn`, `lien`, `btn_lien`, `date_debut`, `statut`) VALUES
(1, 'test1', 'Découvrez les projets innovants des étudiants en MMI. Parcourez les stands, posez vos questions et vivez une expérience immersive !', 'Commencer la visite', 'test_vid.mp4', '', '2026-01-15', 1),
(2, 'test2', 'Découvrez les projets innovants des étudiants en MMI. Parcourez les stands, posez vos questions et vivez une expérience immersive !', 'Commencer la visite', 'https://example.com/videos/intro_jpo_2026.mp4', '', '2026-01-12', 1),
(3, 'test3', 'Découvrez les projets innovants des étudiants en MMI. Parcourez les stands, posez vos questions et vivez une expérience immersive !', 'Commencer la visite', 'https://example.com/videos/intro_jpo_2026.mp4', '', '2026-01-17', 1);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `texte_commentaire` text NOT NULL,
  `satisfaction` int(11) NOT NULL,
  `type_stand` enum('PRESENTATION','MINI JEUX','VIE ETUDIANTE') NOT NULL,
  `mot_clef` varchar(20) NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `reponse` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'Question ouverte',
  `est_publie` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `faq`
--

INSERT INTO `faq` (`id`, `question`, `date_creation`, `reponse`, `statut`, `category`, `est_publie`) VALUES
(2, 'hein ?', '2026-01-12 09:23:24', 'Génial', 0, 'Question ouverte', 1),
(3, 'la bonsoir', '2026-01-12 15:14:15', 'not good ', 0, 'Question ouverte', 0),
(4, 'ouais est-ce que ça marche', '2026-01-12 15:21:57', 'je sais pas trop', 0, 'Question ouverte', 0),
(9, 'c\'est quoi un DATAVISUEL', '2026-01-12 16:21:58', 'efuoegoubaeogubageoubageoubeaougbea', 0, 'Création & Design', 0),
(11, '1 2 1 2', '2026-01-13 09:33:00', '<font color=\"#c62424\">rans nigal</font>', 0, 'Communication', 1),
(12, 'foajn', '2026-01-13 10:41:42', '', 0, 'Question ouverte', 0),
(13, 'ayoooooooooooo', '2026-01-13 10:44:52', '', 0, 'Question ouverte', 0),
(14, 'salut', '2026-01-13 10:50:55', 'yep', 0, 'Infos Générales', 1),
(15, 'ilidane', '2026-01-13 11:00:02', 'hihi', 0, 'Communication', 1),
(16, 'test', '2026-01-13 14:15:05', 'Hello World !', 1, 'Question ouverte', 0),
(17, 'hello je m\'appelle Antoine', '2026-01-13 14:17:09', 'test', 1, 'Question ouverte', 0),
(18, 'emplacement', '2026-01-15 00:11:18', '<i><font color=\"#2b53ca\">Whoupi cela marche&nbsp;</font></i>', 0, 'Lieu & Campus', 1),
(19, 'test', '2026-01-15 16:59:59', '', 0, 'Création & Design', 0),
(20, 'test', '2026-01-15 17:01:26', '', 0, 'Création & Design', 0),
(21, 'hein ?', '2026-01-12 09:23:24', '', 1, 'Question ouverte', 0),
(22, 'la bonsoir', '2026-01-12 15:14:15', 'not good ', 0, 'Question ouverte', 0),
(23, 'ouais est-ce que ça marche', '2026-01-12 15:21:57', 'je sais pas trop', 0, 'Question ouverte', 0),
(24, 'c\'est quoi un DATAVISUEL', '2026-01-12 16:21:58', 'efuoegoubaeogubageoubageoubeaougbea', 0, 'Création & Design', 0),
(25, '1 2 1 2', '2026-01-13 09:33:00', 'rans nigal', 0, 'Communication', 0),
(26, 'test_antoine', '2026-01-13 14:04:14', '', 0, 'Question ouverte', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pole`
--

CREATE TABLE `pole` (
  `id_pole` int(11) NOT NULL,
  `pole_nom` varchar(255) NOT NULL,
  `pole_status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `id_projet` int(11) NOT NULL,
  `pole` enum('CREATION','DEVELOPPEMENT','COMMUNICATION') NOT NULL,
  `projet_titre` varchar(255) NOT NULL,
  `projet_description` text NOT NULL,
  `projet_media_fixe` varchar(255) NOT NULL,
  `projet_media_add` text NOT NULL,
  `projet_visiter` int(11) NOT NULL,
  `statut_projet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id_projet`, `pole`, `projet_titre`, `projet_description`, `projet_media_fixe`, `projet_media_add`, `projet_visiter`, `statut_projet`) VALUES
(1, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(2, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(3, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(4, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(5, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(6, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(7, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(8, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(9, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(10, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(11, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(12, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(13, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(14, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(15, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(16, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(17, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(18, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(19, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(20, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(21, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(22, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(23, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(24, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(25, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(26, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(27, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(28, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(29, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(30, 'CREATION', 'Crea', '<font color=\"#a96a6a\">Reel2</font>', 'img2.jpg', '', 0, 1),
(31, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(32, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1),
(33, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(34, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(35, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(36, '', 'Vidéo Test', 'adadad', 'test_vid.mp4', '', 0, 1),
(37, 'DEVELOPPEMENT', 'Dev', 'Reel1', 'img1.jpg', '', 0, 1),
(38, 'CREATION', 'Crea', 'Reel2', 'img2.jpg', '', 0, 1),
(39, 'COMMUNICATION', 'Com', 'Reel3', 'img3.jpg', '', 0, 1),
(40, '', 'Vidéo Test', 'Reel', 'test_vid.mp4', '', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `stand`
--

CREATE TABLE `stand` (
  `id_stand` int(11) NOT NULL,
  `nom_salle` varchar(50) NOT NULL,
  `titre_stand` varchar(50) NOT NULL,
  `description_stand` varchar(255) NOT NULL,
  `media_fixe` varchar(255) NOT NULL,
  `Media_add` varchar(255) NOT NULL,
  `visiter` int(11) NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `stand`
--

INSERT INTO `stand` (`id_stand`, `nom_salle`, `titre_stand`, `description_stand`, `media_fixe`, `Media_add`, `visiter`, `statut`) VALUES
(1, 'Salle A101', 'DataViz Studio', 'Découvrez comment transformer des données en visuels percutants avec nos outils interactifs.', '218958_medium.mp4', 'test.png;218958_medium.mp4;test.png;218958_medium.mp4', 0, 1),
(2, 'Salle B205', 'GameDev Lab', 'Testez nos mini-jeux développés en Unity et discutez avec les étudiants développeurs.', '218958_medium.mp4', 'test.png;218958_medium.mp4;test.png;218958_medium.mp4', 0, 1),
(3, 'Hall Principal', 'Vie Étudiante MMI', 'Rencontrez les associations étudiantes et découvrez la vie sur le campus !', '218958_medium.mp4', 'test.png;218958_medium.mp4;test.png;218958_medium.mp4', 0, 1),
(4, 'Salle C303', 'Web Innovations', 'Sites web, applications mobiles, UX/UI design – plongez dans l’univers du développement web moderne.', '218958_medium.mp4', 'test.png;218958_medium.mp4;test.png;218958_medium.mp4', 0, 1),
(5, 'co715', 'Exemple', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod nam in sequi maiores optio laboriosam fugiat tempora doloremque distinctio nihil! Nesciunt voluptates ratione facere, voluptatibus iste alias fugit porro voluptatum?\r\nItaque eius, labore aperia', 'exemple.mp4', 'exemple1.mp4;exemple2.png;exemple3.mp4', 0, 0),
(6, 'co715', 'exemple', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod nam in sequi maiores optio laboriosam fugiat tempora doloremque distinctio nihil! Nesciunt voluptates ratione facere, voluptatibus iste alias fugit porro voluptatum?\r\nItaque eius, labore aperia', 'exemple.mp4', 'exemple1.mp4;exemple2.mp4;exemple3.png', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','livre_or','visiteur','stand') NOT NULL,
  `statut_actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  `prenom_user` varchar(100) NOT NULL,
  `parcours_user` varchar(100) NOT NULL,
  `point_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`, `password`, `role`, `statut_actif`, `date_creation`, `prenom_user`, `parcours_user`, `point_user`) VALUES
(1, 'Hamlaoui', 'jawad.hamlaoui@gmail.com', '$2y$10$3lsMOYknH3f0OBpihrHP2.2QYw5IpYNFsa2bJWRIN8TDlhugJFqDu', 'admin', 1, '2026-01-12 20:11:16', 'Jawad', '', 0),
(2, 'Visiteur', 'visiteur@gmail.com', 'visiteur', 'visiteur', 1, '2026-01-13 16:15:29', 'Visiteur', '', 0),
(3, 'McQueen', 'flash.mcqueen@gmail.com', 'flash', 'visiteur', 1, '2026-01-13 16:31:03', 'Flash', '', 0),
(4, 'test', 'a@a', '$2y$10$r9YgvHk6tONkrva4BeVImuYZzgDN44k9mypeet.5ILJANzKet6Ifi', 'visiteur', 1, '2026-01-13 17:21:17', 'A renseigner', 'Non défini', 0),
(5, '', 'a@a.com', '$2y$10$kbQbHoVQQMJuoimNIBIcWu3UpBjctNRrjVJayAmJQdRlG6q3FlNLm', 'admin', 1, '2026-01-13 22:20:27', '', '', 0),
(6, 'Dinoco', 'dinoco@gmail.com', 'dinoco', 'visiteur', 1, '2026-01-14 00:51:06', '', '', 0),
(7, 'A compléter', 'sally@gmail.com', '$2y$10$4nSVLKg7GJpsf8OEKkgMs.xLNUG4G5snXOPkTNtjYLTWfM9TUBIqW', 'visiteur', 1, '2026-01-14 01:58:03', 'A compléter', 'En attente', 0),
(8, 'A compléter', 'martin@gmail.com', '$2y$10$WHGroJL5BZXTXsM3.hVMNeMMbLd6vgpgdx8WqdQLHHe.m5nVHhgZa', 'visiteur', 1, '2026-01-14 02:01:24', 'A compléter', 'En attente', 0),
(9, '', 'illidanm83@gmail.com', '$2y$10$2U1sRcARc.V05KMRyF.3qOzYC/Dcj6uzsUFy7iy07NstWVH52VzFm', 'admin', 1, '2026-01-14 20:28:34', '', '', 0),
(10, 'Bond', 'james.bond@gmail.com', 'jamesbond', 'visiteur', 1, '2026-01-15 00:27:31', 'James', '', 0),
(11, '', 'mikatestqr@membre.com', '$2y$10$82plv1XXvhmWWQXiW7MlI.EEcPjbBjpodz7ip0WdpIhqPtnU/9FMm', 'admin', 1, '2026-01-15 14:27:00', '', '', 0),
(12, '', 'jean.test@gmail.com', '$2y$10$98UogCcfOq2h/nWD5HuxjuTG8etEpAk7EPJRHHOTmFnLk9YEJnMfC', 'admin', 1, '2026-01-15 18:21:52', '', '', 0),
(13, '', 'evan@gmail.com', '$2y$10$bk3Qo8RVUYeJa3TTm6DNVO9tYpcxZAF6dOky3V6qFgk5oCElR1AsG', 'visiteur', 1, '2026-01-15 18:36:48', '', '', 0),
(14, 'A compléter', 'ben@ben.fr', '$2y$10$.peNSG3w4jhIB0qheoK54uH8DWX9.IMxTkWevSh/FVKdTKFLq9J0S', 'visiteur', 1, '2026-01-15 19:36:49', 'A compléter', 'En attente', 0),
(15, 'A compléter', 'test@test.com', '$2y$10$P0qPFMPNsef90tTbM15AH.2XTkLEzQp0WLHS2LkFdEFHxuXPFayi2', 'visiteur', 1, '2026-01-15 20:02:16', 'A compléter', 'En attente', 0),
(16, '', 'thomas@gmail.com', '$2y$10$13vfRCAMj.QHnAHIiAheguLgmGGKCnRp1qaGTCfdzMuXy9IcMssgO', 'admin', 1, '2026-01-15 21:20:41', '', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_like`
--

CREATE TABLE `user_like` (
  `id` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_stand` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `user_like`
--

INSERT INTO `user_like` (`id`, `id_projet`, `id_stand`, `id_user`, `statut`) VALUES
(15, 1, 0, 5, 1),
(16, 0, 1, 5, 1),
(17, 0, 4, 5, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accueil`
--
ALTER TABLE `accueil`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pole`
--
ALTER TABLE `pole`
  ADD PRIMARY KEY (`id_pole`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`id_projet`);

--
-- Index pour la table `stand`
--
ALTER TABLE `stand`
  ADD PRIMARY KEY (`id_stand`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_like`
--
ALTER TABLE `user_like`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accueil`
--
ALTER TABLE `accueil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `pole`
--
ALTER TABLE `pole`
  MODIFY `id_pole` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `id_projet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `stand`
--
ALTER TABLE `stand`
  MODIFY `id_stand` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user_like`
--
ALTER TABLE `user_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
