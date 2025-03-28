<?php

namespace app\Model;
use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class Apply {
    private $pdo; // Stocker la connexion à la base de données

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getApplyById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE idapplication = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un seul enregistrement
    }

    public function getAllApply() {
        $stmt = $this->pdo->query("SELECT * FROM applications");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeApply($id) {
        $stmt = $this->pdo->prepare("DELETE FROM applications WHERE idapplication = ?");
        return $stmt->execute([$id]);
    }

    public function removeAllApply() {
        $stmt = $this->pdo->prepare("DELETE FROM applications");
        return $stmt->execute();
    }

    public function storeApply($CvApply,$LetterApply,$DateApply) {
        if (empty($CvApply) || empty($LetterApply) || empty($DateApply) ) {
            return false;
        }
        
        $stmt = $this->pdo->prepare("INSERT INTO applications (idapplication, cvapplication, coverletterapplication) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$CvApply, $LetterApply, $DateApply]);
    }

    public function editApply($id, $newData) {
        $stmt = $this->pdo->prepare("UPDATE applications SET idapplication = ?, cvapplication = ?, coverletterapplication = ? WHERE Id_Application = ?");
        return $stmt->execute([$newData['idapplication'], $newData['cvapplication'], $newData['coverletterapplication'], $id]);
    }
}


?>