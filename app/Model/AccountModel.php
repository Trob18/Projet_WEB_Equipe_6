<?php

namespace app\Model;
use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class Account {
    private $pdo; // Stocker la connexion à la base de données

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAccount($column, $value, $selectColumn = '*') {
        
        $validColumns = ['Id_Account', 'LastName_Account', 'FirstName_Account', 'Email_Account', 'Password_Account', 'Image_Account', 'Description_Account', 'Address_Account', 'PhoneNumber_Account', 'Studies_Account', 'Id_Roles'];
        if (!in_array($column, $validColumns)) {
            return "Colonne invalide!";
        }
    
        // Sélectionner la colonne spécifique demandée (par défaut '*')
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM Accounts WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getAllAccounts() {
        $stmt = $this->pdo->prepare("SELECT * FROM Accounts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeAccount($lastName, $firstName, $email, $password) {
        if (empty($lastName) || empty($firstName) || empty($email) || empty($password)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("INSERT INTO Accounts (LastName_Account, FirstName_Account, Email_Account, Password_Account) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$lastName, $firstName, $email, $hashedPassword]);
    }

    public function removeAccount($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Accounts WHERE Id_Account =    ?");
        return $stmt->execute([$id]);
    }

    public function removeAllAccounts() {
        $stmt = $this->pdo->prepare("DELETE FROM Accounts");
        return $stmt->execute();
    }

    public function editAccount($id, $newData) {
        $stmt = $this->pdo->prepare("UPDATE Accounts SET LastName_Account = ?, FirstName_Account = ?, Email_Account = ? WHERE Id_Account = ?");
        return $stmt->execute([$newData['lastName'], $newData['firstName'], $newData['email'], $id]);
    }
}


?>
