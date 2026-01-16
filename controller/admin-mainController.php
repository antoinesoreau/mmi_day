<?php
require_once "./models/admin-dataModel.php";

class MainController
{
    private $model;

    public function __construct()
    {
        $this->model = new DataModel();
    }

    public function show($type)
    {
        $data = $this->model->getAll($type);
        $viewFile = "./views/admin-gestion_" . $type . ".php";

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("Erreur : La vue gestion_$type.php est introuvable.");
        }
    }

    public function saveContent()
    {
        header("Content-Type: application/json");
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data) {
            $res = $this->model->update(
                $data["table"],
                $data["id"],
                $data["html"],
            );
            echo json_encode(["status" => $res ? "success" : "error"]);
        }
    }

    public function addContent()
    {
        $type = $_GET["type"] ?? "";
        $mediaPath = "";

        // 1. Gestion de l'image (Projets uniquement)
        if (isset($_FILES["media"]) && $_FILES["media"]["error"] === 0) {
            $mediaPath = "uploads/" . time() . "_" . $_FILES["media"]["name"];
            move_uploaded_file($_FILES["media"]["tmp_name"], $mediaPath);
        }

        // 2. Logique d'ajout selon le type
        if ($type === "projet") {
            $this->model->create("projet", [
                "titre" => $_POST["titre"],
                "pole" => $_POST["pole"],
                "desc" => $_POST["description"],
                "media" => $mediaPath,
            ]);
        } elseif ($type === "stand") {
            // CORRECTION : On ajoute la clé 'salle' récupérée du formulaire
            // Cela permet de remplir les 3 '?' attendus par le modèle (salle, nom, desc)
            $this->model->create("stand", [
                "nom" => $_POST["nom"] ?? "Sans titre",
                "desc" => $_POST["description"] ?? "",
                "salle" => $_POST["salle"] ?? "Non définie",
            ]);
        }

        header("Location: admin.php?action=$type");
        exit();
    }
}
