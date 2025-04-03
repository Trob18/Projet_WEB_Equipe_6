<?php

namespace app\Model;

use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';

class AccountModel {
    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
   
    

    public function getAccount($column, $value, $selectColumn = '*') {
        $validColumns = [
            'Id_Account', 'LastName_Account', 'FirstName_Account', 'Email_Account', 
            'Password_Account', 'Image_Account', 'Description_Account', 'Address_Account', 
            'PhoneNumber_Account', 'Studies_Account', 'Id_Roles'
        ];
    
        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM Accounts WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }
    
        return $result ?: null;
    }
    


    
    public function getAllAccounts() {
        $stmt = $this->pdo->query("SELECT * FROM Accounts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeAccount($lastName, $firstName, $dateNaissance, $email, $telephone, $password, $type) {
        if (empty($lastName) || empty($firstName)|| empty($dateNaissance) || empty($email) || empty($telephone) || empty($password) || empty($type)) {
            return false;
        }


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO accounts (LastName_Account, FirstName_Account, BirthDate_Account, Email_Account, PhoneNumber_Account, Password_Account, Id_Roles) 
            VALUES (:lastName, :firstName, :dateNaissance, :email, :telephone, :password, :type)
        ");
        $stmt->execute([
            'lastName' => $lastName,
            'firstName' => $firstName,
            'dateNaissance' => $dateNaissance,
            'email' => $email,
            'telephone' => $telephone,
            'password' => $hashedPassword,
            'type' => $type
        ]);
        return TRUE;
    }

    public function removeAccount($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Accounts WHERE Id_Account = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function removeAllAccounts() {
        $stmt = $this->pdo->prepare("DELETE FROM Accounts");
        return $stmt->execute();
    }

    public function editAccount($id, $newData) {
        $validColumns = [
            'LastName_Account', 'FirstName_Account', 'Email_Account', 'Password_Account', 
            'Image_Account', 'Description_Account', 'Address_Account', 'PhoneNumber_Account', 
            'Studies_Account', 'Id_Roles'
        ];

        $setParts = [];
        $params = ['id' => $id];

        foreach ($newData as $key => $value) {
            if (in_array($key, $validColumns)) {
                $setParts[] = "$key = :$key";
                $params[$key] = $key === 'Password_Account' ? password_hash($value, PASSWORD_DEFAULT) : $value;
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE Accounts SET " . implode(', ', $setParts) . " WHERE Id_Account = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }


    public function getTotalAccount(){
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM accounts");
        return $stmt->fetchColumn();
    }

    public function getAccountWithPagination($limit, $offset){
        $limit = max(1, (int) $limit);
        $offset = max(0, (int) $offset); 
    
        $stmt = $this->pdo->prepare("SELECT * FROM accounts ORDER BY Id_Account ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function searchAccounts($searchName, $limit, $offset){
        $query = "SELECT * FROM accounts WHERE 1=1";
    
        if (!empty($searchName)) {
            $query .= " AND FirstName_Account LIKE :searchName";
        }
    
        $query .= " ORDER BY Id_Account ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($query);
    
        if (!empty($searchName)) {
            $stmt->bindValue(':searchName', '%' . $searchName . '%', PDO::PARAM_STR);
        }
    
    
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function uploadimg($userId, $imageUrl){
        $stmt = $this->pdo->prepare("
        UPDATE accounts 
        SET Image_Account = :image_url 
        WHERE Email_Account = :userId
        ");
        $stmt->execute([
            'image_url' => $imageUrl,
            'userId' => $userId
        ]);
        return TRUE;
    }

}