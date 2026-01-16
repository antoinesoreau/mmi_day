<?php
require_once "./config/database.php";

class DataModel
{
    private $pdo;
    public function __construct()
    {
        // On rend la variable $dtb du fichier database.php accessible ici
        global $dtb;

        // On l'assigne à notre propriété locale
        $this->pdo = $dtb;
    }

    public function getAll($table)
    {
        $t = strtolower($table);
        if ($t === "projet") {
            $pk = "id_projet";
        } elseif ($t === "stand") {
            $pk = "id_stand";
        } else {
            $pk = "id";
        }

        $stmt = $this->pdo->query("SELECT * FROM $table ORDER BY $pk DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($table, $data)
    {
        $t = strtolower($table);

        if ($t === "projet") {
            $sql =
                "INSERT INTO projet (projet_titre, pole, projet_description, projet_media_fixe, statut_projet) VALUES (?, ?, ?, ?, 1)";
            return $this->pdo
                ->prepare($sql)
                ->execute([
                    $data["titre"],
                    $data["pole"],
                    $data["desc"],
                    $data["media"],
                ]);
        } elseif ($t === "stand") {
            // SQL a 3 tokens (?) : nom_salle, titre_stand, description_stand
            $sql = "INSERT INTO Stand (nom_salle, titre_stand, description_stand, media_fixe, Media_add, visiter, statut)
                VALUES (?, ?, ?, '', '', 0, 1)";

            // CORRECTION : On doit passer exactement 3 variables pour les 3 '?'
            return $this->pdo->prepare($sql)->execute([
                $data["salle"], // 1er ? -> nom_salle
                $data["nom"], // 2ème ? -> titre_stand
                $data["desc"], // 3ème ? -> description_stand
            ]);
        }
    }

    public function update($table, $id, $content)
    {
        $t = strtolower($table);
        if ($t === "projet") {
            $sql =
                "UPDATE projet SET projet_description = ? WHERE id_projet = ?";
        } elseif ($t === "stand") {
            $sql = "UPDATE Stand SET description_stand = ? WHERE id_stand = ?";
        } else {
            $sql = "UPDATE faq SET reponse = ?, est_publie = 1 WHERE id = ?";
            $sql = "UPDATE faq SET reponse = ?, statut = 1 WHERE id = ?";
        }
        return $this->pdo->prepare($sql)->execute([$content, $id]);
    }
}
