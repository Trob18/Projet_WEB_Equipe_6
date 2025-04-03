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

    public function getCompany($column, $value, $selectColumn = '*')
    {
        $validColumns = [
            'Id_Company',
            'Name_Company',
            'Image_Company',
            'Email_Company',
            'Address_Company',
            'Description_Company'
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






    public function storeCompany($firstName, $email, $address, $description) {
        if (empty($firstName) || empty($email) || empty($address) || empty($description)) {
            return false;
        }
        
        $stmt = $this->pdo->prepare("
            INSERT INTO companies (Name_Company, Email_Company, Address_Company, Description_Company)
            VALUES (:Name_Company, :Email_Company, :Address_Company, :Description_Company)
        ");
        
        $stmt->execute([
            'Name_Company' => $firstName,
            'Email_Company' => $email,
            'Address_Company' => $address,
            'Description_Company' => $description
        ]);
        
        return true;
    }







    public function getAllCompany()
    {
        $stmt = $this->pdo->query("SELECT * FROM companies ORDER BY Id_Company ASC"); // dans l'ordre croissant pour éviter les renvoie dans tous le sens
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeCompany($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM companies WHERE Id_Company = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function removeAllCompany()
    {
        $stmt = $this->pdo->prepare("DELETE FROM companies");
        return $stmt->execute();

    }

    

    public function editCompany($id, $newData)
    {
        $validColumns = [
            'Description_Company', 'Address_Company'
        ];

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

        $sql = "UPDATE companies SET " . implode(', ', $setParts) . " WHERE Id_Company = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }





















    public function getCompaniesWithPagination($limit, $offset)
{
    $limit = max(1, (int) $limit);  // S'assurer que la limite est au moins 1
    $offset = max(0, (int) $offset); // S'assurer que l'offset est au moins 0

    $stmt = $this->pdo->prepare("SELECT * FROM companies ORDER BY Id_Company ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getTotalCompanies()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM companies");
        return $stmt->fetchColumn();
    }

    public function searchCompanies($searchName, $searchLocation, $limit, $offset)
    {
        $query = "SELECT * FROM companies WHERE 1=1";
    
        if (!empty($searchName)) {
            $query .= " AND Name_Company LIKE :searchName";
        }
    
        if (!empty($searchLocation)) {
            $query .= " AND Address_Company LIKE :searchLocation";
        }
    
        $query .= " ORDER BY Id_Company ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($query);
    
        if (!empty($searchName)) {
            $stmt->bindValue(':searchName', '%' . $searchName . '%', PDO::PARAM_STR);
        }
    
        if (!empty($searchLocation)) {
            $stmt->bindValue(':searchLocation', '%' . $searchLocation . '%', PDO::PARAM_STR);
        }
    
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompanyById($id) {
        $query = "SELECT * FROM companies WHERE Id_Company = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }







    public function uploadimgC($userId, $imageUrl){
        $stmt = $this->pdo->prepare("
        UPDATE companies 
        SET Image_Company = :image_url 
        WHERE Email_Company = :userId
        ");
        $stmt->execute([
            'image_url' => $imageUrl,
            'userId' => $userId
        ]);
        return TRUE;
    }
}











































