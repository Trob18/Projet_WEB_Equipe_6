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

    public function getApply($column, $value, $selectColumn = '*')
    {
        $validColumns = [
            'Id_Application',
            'Cv_Application',
            'CoverLetter_Application',
            'Date_Application',
            'Id_Account',
            'Id_Offer'
        ];

        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }

        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM applications WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }

        return $result ?: null;
    }

    public function getAllApply()
    {
        $stmt = $this->pdo->query("SELECT * FROM applications ORDER BY Id_Application ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeApply($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM applications WHERE Id_Application = ?");
        return $stmt->execute([$id]);
    }

    public function removeAllApply()
    {
        $stmt = $this->pdo->prepare("DELETE  FROM applications");
        return $stmt->execute();
    }

    public function storeApply($IdApply, $CvApply, $LetterApply, $DateApply)
    {
        if (empty($CvApply) || empty($LetterApply) || empty($DateApply) || empty($IdApply)) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO applications (Id_Application, Cv_Application, CoverLetter_Application, Date_Application) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$IdApply, $CvApply, $LetterApply, $DateApply]);
    }

    public function editApply($id, $newData)
    {
        $validColumns = ['Cv_Application', 'CoverLetter_Application','Id_Application','Date_Application'];
        $setParts = [];
        $params = ['id' => $id];

        foreach ($newData as $key => $value) {
            if (in_array($key, $validColumns)) {
                $setParts[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE applications SET " . implode(', ', $setParts) . " WHERE Id_Application = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }
}


?>