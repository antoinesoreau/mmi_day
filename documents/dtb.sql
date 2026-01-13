-- Table user
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'livre_or', 'visiteur') NOT NULL,
    statut_actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table pole
CREATE TABLE pole (
    id_pole INT PRIMARY KEY AUTO_INCREMENT,
    pole_nom VARCHAR(255) NOT NULL,
    pole_status TINYINT(1) NOT NULL DEFAULT 1
);

-- Table rubrique
CREATE TABLE rubrique (
    id_rubrique INT PRIMARY KEY AUTO_INCREMENT,
    rubrique_nom VARCHAR(255) NOT NULL,
    rubrique_status TINYINT(1) NOT NULL DEFAULT 1
);

-- Table commentaire
CREATE TABLE commentaire (
    id_commentaire INT PRIMARY KEY AUTO_INCREMENT,
    commentaire_nom VARCHAR(255) NOT NULL, -- "Nom de la personne ou anonyme"
    commentaire_ressenti TINYINT(1) NOT NULL, -- 1 = happy, 2 = sad, 3 = middle (ou à adapter)
    commentaire_note TINYINT(1) NOT NULL CHECK (commentaire_note BETWEEN 1 AND 5),
    id_profile ENUM('lyceen-etudiant', 'parent', 'entreprise', 'presse') NOT NULL,
    id_rubrique INT NOT NULL,
    commentaire_texte TEXT NOT NULL,
    commentaire_status TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (id_rubrique) REFERENCES rubrique(id_rubrique) ON DELETE CASCADE
);

-- Table commentaire_pole (table de liaison many-to-many entre commentaire et pole)
CREATE TABLE commentaire_pole (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_commentaire INT NOT NULL,
    id_pole INT NOT NULL,
    FOREIGN KEY (id_commentaire) REFERENCES commentaire(id_commentaire) ON DELETE CASCADE,
    FOREIGN KEY (id_pole) REFERENCES pole(id_pole) ON DELETE CASCADE,
    UNIQUE KEY unique_commentaire_pole (id_commentaire, id_pole)
);