<?php

namespace app\Model;
use PDO;


require_once __DIR__ . '/../../config/ConfigDatabase.php';

class Account {
    private $pdo; // Stocker la connexion à la base de données

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllAccounts() {
        $stmt = $this->pdo->query("SELECT * FROM Accounts");
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

    public function getAccountByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Accounts WHERE Email_Account = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function removeAccount($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Accounts WHERE Id_Account = ?");
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