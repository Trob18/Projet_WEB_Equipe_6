<?php

namespace app\Model;

use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class ApplyModel
{
    private $pdo;

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
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE Id_Account = :id ORDER BY Id_Application ASC");
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
        $stmt = $this->pdo->prepare("DELETE * FROM applications");
        return $stmt->execute();
    }

    public function StoreApply($IdAccount, $cvPath, $CoverLetter, $dateApply, $IdOffer)
    {
        if (empty($IdAccount) || empty($IdOffer) || empty($cvPath)) {
            return "Erreur : Informations manquantes pour l'enregistrement.";
        }
        $query = 'INSERT INTO applications (IdAccount, IdOffer, CvFile, CoverLetter, DateApply) 
              VALUES (:IdAccount, :IdOffer, :CvFile, :CoverLetter, :DateApply)';

        $stmt = $this->pdo->prepare($query);

        if ($stmt->execute([
            ':IdAccount' => $IdAccount,
            ':IdOffer' => $IdOffer,
            ':CvFile' => $cvPath,
            ':CoverLetter' => $CoverLetter,
            ':DateApply' => $dateApply
        ])) {
            return true; 
        } else {
            return "Erreur lors de l'enregistrement de la candidature dans la base de données."; 
        }
    }



    public function editApply($id, $newData)
    {
        $stmt = $this->pdo->prepare("UPDATE applications SET Cv_Application = ?, CoverLetterApplication = ? WHERE Id_Application = ?");
        return $stmt->execute([$newData['Cv_Application'], $newData['CoverLetterApplication'], $id]);
    }

    public function storeApplication($id, $IdOffer, $coverLetter, $cvNameUnique) {
        $sql = "INSERT INTO Applications (Id_Account, Id_Offer, CoverLetter_Application, Cv_Application) 
                VALUES (:Id_Account, :Id_Offer, :CoverLetter, :CV)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'Id_Account' => $id,
            'Id_Offer' => $IdOffer,
            'CoverLetter' => $coverLetter,
            'CV' => $cvNameUnique
        ]);
    }
}