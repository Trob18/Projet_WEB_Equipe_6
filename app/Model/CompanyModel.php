<?php

namespace app\Model;
use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class CompanyModel
{
    private $pdo; // Stocker la connexion à la base de données

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCompany($column, $value, $selectColumn = '*'){
        $validColumns = [
            'Id_Company', 'Name_Company', 'Image_Company', 'Email_Company', 
            'Address_Company', 'Description_Company'
        ];
    
        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }
    
        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM companies WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }
    
        return $result ?: null;
    }

    public function getAllCompany()
    {
        $stmt = $this->pdo->query("SELECT * FROM companies ORDER BY Id_Company ASC"); // dans l'ordre croissant pour éviter les renvoie dans tous le sens
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeCompany($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM companies WHERE Id_Company = ?");
        return $stmt->execute([$id]);
    }

    public function removeAllCompany()
    {
        $stmt = $this->pdo->prepare("DELETE * FROM companies");
        return $stmt->execute();
    }

    public function StoreCompany($IdCompany,$NameCompany,$ImageCompany,$EmailCompany,$AdresseCompany,$DescriptionCompany)
    {
        if (empty($IdCompany) || empty($NameCompany) || empty($ImageCompany) || empty($EmailCompany) || empty($AdresseCompany) || empty($DescriptionCompany) ) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO companies (Id_Company,Name_Company,Image_Company,Email_Company,Address_Company,Description_Company) VALUES (?, ?, ?, ?,?, ?)");
        return $stmt->execute([$IdCompany,$NameCompany,$ImageCompany,$EmailCompany,$AdresseCompany,$DescriptionCompany]);
    }

    public function editCompany($id, $newData)
    {
        $stmt = $this->pdo->prepare("UPDATE companies SET Name_Company = ?, Image_Company = ?, Email_Company = ?, Address_Company = ?, Description_Company = ? WHERE Id_Company = ?");
        return $stmt->execute([$newData['Name_Company'], $newData['Image_Company'], $newData['Email_Company'], $newData['Adresse_Company'], $newData['Description_Company'], $newData['CoverLetterApplication'], $id]);
    }
}


?>