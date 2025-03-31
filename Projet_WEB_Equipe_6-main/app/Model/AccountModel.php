<?php

namespace app\Model;

use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';

class AccountModel {
    private $pdo; // Connexion à la base de données

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
   
    

    public function getAccount($column, $value, $selectColumn = '*') {
        // Liste des colonnes valides pour éviter l'injection SQL
        $validColumns = [
            'Id_Account', 'LastName_Account', 'FirstName_Account', 'Email_Account', 
            'Password_Account', 'Image_Account', 'Description_Account', 'Address_Account', 
            'PhoneNumber_Account', 'Studies_Account', 'Id_Roles'
        ];
    
        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }
    
        // Sélectionner la colonne spécifique demandée
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

    public function storeAccount($lastName, $firstName, $email, $password) {
        if (empty($lastName) || empty($firstName) || empty($email) || empty($password)) {
            return false;
        }

        // Vérifier si l'email existe déjà
        if ($this->getAccount('Email_Account', $email)) {
            return "Email déjà utilisé!";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO Accounts (LastName_Account, FirstName_Account, Email_Account, Password_Account) 
            VALUES (:lastName, :firstName, :email, :password)
        ");
        return $stmt->execute([
            'lastName' => $lastName,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword
        ]);
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
}


