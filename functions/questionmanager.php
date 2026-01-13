<?php

class QuestionManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPublishedQuestions()
    {
        $sql =
            "SELECT * FROM faq WHERE statut = 1 ORDER BY date_creation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllQuestions()
    {
        $sql = "SELECT * FROM faq ORDER BY statut ASC, date_creation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addQuestion($question, $category)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO faq (question, category, date_creation, statut) VALUES (?, ?, NOW(), 0)",
        );
        return $stmt->execute([$question, $category]);
    }

    public function answerAndPublish($id, $reponse)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE faq SET reponse = ?, statut = 1 WHERE id = ?",
        );
        return $stmt->execute([$reponse, $id]);
    }

    public function getCategories()
    {
        return [
            "Question ouverte",
            "Développement Web",
            "Création & Design",
            "Communication",
            "Lieu & Campus",
            "Infos Générales",
            "Vie Étudiante",
        ];
    }
}
