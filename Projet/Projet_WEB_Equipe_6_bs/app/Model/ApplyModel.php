<?php

namespace app\Model;
use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class ApplyModel
{
    private $pdo; // Stocker la connexion à la base de données

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getApplyById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE Id_Application = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un seul enregistrement
    }

    public function getAllApply()
    {
        $stmt = $this->pdo->query("SELECT * FROM applications");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeApply($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM applications WHERE Id_Application = ?");
        return $stmt->execute([$id]);
    }

    public function removeAllApply()
    {
        $stmt = $this->pdo->prepare("DELETE * FROM applications");
        return $stmt->execute();
    }

    public function storeApply($IdApply,$CvApply,$LetterApply,$DateApply)
    {
        if (empty($CvApply) || empty($LetterApply) || empty($DateApply) || empty($IdApply) ) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO applications (Id_Application, Cv_Application, CoverLetter_Application, Date_Application) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$IdApply,$CvApply, $LetterApply, $DateApply]);
    }

    public function editApply($id, $newData)
    {
        $stmt = $this->pdo->prepare("UPDATE applications SET Id_Application = ?, Cv_Application = ?, CoverLetterApplication = ? WHERE Id_Application = ?");
        return $stmt->execute([$newData['Id_Application'], $newData['Cv_Application'], $newData['CoverLetterApplication'], $id]);
    }
}


?>