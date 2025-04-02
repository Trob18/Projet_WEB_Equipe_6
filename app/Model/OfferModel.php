<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class OfferModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getOffer($column, $value, $selectColumn = '*') {
        // Liste des colonnes valides pour éviter l'injection SQL
        $validColumns = [
            'Id_Offer', 'Title_Offer', 'Contract_Offer', 'Address_Offer', 
            'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer'
        ];

        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }

        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM offers WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }

        return $result ?: null;
    }

    public function getAllOffers(){
        $stmt = $this->pdo->prepare("SELECT * FROM offers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeOffer($newdata) {
        $stmt = $this->pdo->prepare("
                INSERT INTO offers (
                    Title_Offer, 
                    Contract_Offer, 
                    Address_Offer, 
                    ActivitySector_Offer, 
                    Salary_Offer, 
                    Description_Offer, 
                    Id_Company
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
    
        $result = $stmt->execute([
                $newdata['Title_Offer'],
                $newdata['Contract_Offer'],
                $newdata['Address_Offer'],
                $newdata['ActivitySector_Offer'],
                $newdata['Salary_Offer'],
                $newdata['Description_Offer'],
                $newdata['Id_Company']
            ]);
    
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    public function removeOffer($id){
        $stmt = $this->pdo->prepare("DELETE FROM offers WHERE Id_Offer = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function removeAllOffers(){
        $stmt = $this->pdo->prepare("DELETE FROM offers");
        return $stmt->execute();
    }

    public function editOffer($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE offers SET Title_Offer = ?, Contract_Offer = ?, Address_Offer = ?, ActivitySector_Offer = ?, Salary_Offer = ?, Description_Offer = ?, Id_Company = ? WHERE Id_Offer = ?");
        return $stmt->execute([
            $newdata['Title_Offer'],
            $newdata['Contract_Offer'],
            $newdata['Address_Offer'],
            $newdata['ActivitySector_Offer'],
            $newdata['Salary_Offer'],
            $newdata['Description_Offer'],
            $newdata['Id_Company'],
            $id
        ]);
    }
}
?>