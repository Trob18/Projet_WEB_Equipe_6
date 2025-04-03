<?php

namespace app\Model;

use PDO;

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    public function CountgetAllApply($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE Id_Application = :id ORDER BY Id_Application ASC");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeApply($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM applications WHERE Id_Application = ?");
        return $stmt->execute([$id]);
    }

    public function removeAllApply()
    {
        $stmt = $this->pdo->prepare("DELETE FROM applications");
        return $stmt->execute();
    }



    public function editApply($id, $newData)
    {
        $stmt = $this->pdo->prepare("UPDATE applications SET Cv_Application = ?, CoverLetter_Application = ? WHERE Id_Application = ?");
        return $stmt->execute([$newData['Cv_Application'], $newData['CoverLetter_Application'], $id]);
    }

    public function storeApplication($id, $IdOffer, $coverLetter, $cvNameUnique, $id_account) {
        $sql = "INSERT INTO applications (Id_Application, Id_Offer, CoverLetter_Application, Cv_Application, Id_Account) 
                VALUES (:Id_Application, :Id_Offer, :CoverLetter_Application, :Cv_Application, :Id_Account)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':Id_Application' => $id,
            ':Id_Offer' => $IdOffer,
            ':CoverLetter_Application' => $coverLetter,
            ':Cv_Application' => $cvNameUnique,
            ':Id_Account' => $id_account
        ]);
    }
}