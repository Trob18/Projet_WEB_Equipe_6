<?php

namespace app\Test;

use app\Model\AccountModel;
use PHPUnit\Framework\TestCase;


require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\config\ConfigDatabase2.php';
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\app\Model\AccountModel.php';




class AccountTest extends TestCase
{
    private $account;
    private $pdo;
    private $accountController;

    protected function setUp(): void
    {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $this->pdo = $configDatabase->getConnection();
        $this->account = new AccountModel($this->pdo);

        // Suppression du compte de test s'il existe déjà
        $this->pdo->exec("DELETE FROM accounts WHERE Email_Account = 'johndoe@example.com'");

        // Insertion du compte de test
        $stmt = $this->pdo->prepare("INSERT INTO accounts 
            (LastName_Account, FirstName_Account, Email_Account, Password_Account, Image_Account, Description_Account, Address_Account, PhoneNumber_Account, Studies_Account, Id_Roles) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Doe', 'John', 'johndoe@example.com', 'hashedpassword', '', '', '', 123456789, '', 1]);
    }

    protected function tearDown(): void
    {
        // Suppression du compte de test après chaque test
        $this->pdo->exec("DELETE FROM accounts WHERE Email_Account = 'johndoe@example.com'");
    }

    public function testStoreAccount()
    {
        $this->pdo->exec("DELETE FROM accounts WHERE Email_Account = 'johndoe@example.com'");
        // Tentative d'insertion du compte
        $result = $this->account->storeAccount("Doe", "John", "20-05-2025","johndoe@example.com", "0326154815","password123",2);
        $this->assertTrue($result);

        // Vérifier si le compte a bien été ajouté
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();


        // Validation des informations du compte
        $this->assertNotEmpty($account);
        $this->assertEquals("Doe", $account['LastName_Account']);
        $this->assertEquals("John", $account['FirstName_Account']);
    }

    public function testGetAccountByEmail()
    {
        // Récupération du compte par email avec la méthode getAccount
        $account = $this->account->getAccount('Email_Account', 'johndoe@example.com');

        // Validation des informations du compte récupéré
        $this->assertIsArray($account);
        $this->assertEquals('Doe', $account['LastName_Account']);
        $this->assertEquals('John', $account['FirstName_Account']);
        $this->assertEquals('johndoe@example.com', $account['Email_Account']);
    }

    public function testRemoveAccount()
    {
        // Récupérer l'ID du compte avant la suppression
        $stmt = $this->pdo->prepare("SELECT Id_Account FROM accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();

        $this->assertNotEmpty($account);
        $accountId = $account['Id_Account'];

        // Tentative de suppression du compte
        $result = $this->account->removeAccount($accountId);
        $this->assertTrue($result);

        // Vérification que le compte a bien été supprimé
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE Id_Account = ?");
        $stmt->execute([$accountId]);
        $account = $stmt->fetch();

        // Vérification que le compte n'existe plus dans la base de données
        $this->assertEmpty($account);
    }

    public function testGetAllaccounts()
    {
        // Récupération du compte de John Doe par son email
        $account = $this->account->getAccount('Email_Account', 'johndoe@example.com');

        // Vérification que le compte est bien récupéré
        $this->assertNotEmpty($account);
        $this->assertEquals('Doe', $account['LastName_Account']);
        $this->assertEquals('John', $account['FirstName_Account']);
        $this->assertEquals('johndoe@example.com', $account['Email_Account']);
    }


    public function testEditaccounts()
    {
        $stmt = $this->pdo->prepare("SELECT Id_Account FROM accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();

        $newData = [
            'FirstName_Account' => "Pierre",
        ];

        // Récupération du compte de John Doe par son email
        $this->account->editAccount($account['Id_Account'], $newData);

        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();

        // Vérification que le compte est bien récupéré
        $this->assertNotEmpty($account);
        $this->assertEquals('Doe', $account['LastName_Account'],'oui');
        $this->assertEquals('Pierre', $account['FirstName_Account']);
        $this->assertEquals('johndoe@example.com', $account['Email_Account']);
    }
}