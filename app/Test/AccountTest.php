<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\Account;
use PDO;

// Utilisation d'une inclusion conditionnelle pour éviter les redéclarations
if (!class_exists('app\Config\ConfigDatabase')) {
    require_once __DIR__ . '/../../config/ConfigDatabase.php';
}

require_once __DIR__ . '/../Model/AccountModel.php';

class AccountTest extends TestCase {
    private $account;
    private $pdo;
    private $accountController;

    protected function setUp(): void {
        // Connexion à la base de données sans réinclure le fichier
        $config = new \app\Config\ConfigDatabase();
        $this->pdo = $config->connect();
        
        $this->account = new Account($this->pdo);

        // Suppression du compte de test s'il existe déjà
        $this->pdo->exec("DELETE FROM Accounts WHERE Email_Account = 'johndoe@example.com'");

        // Insertion du compte de test
        $stmt = $this->pdo->prepare("INSERT INTO Accounts 
            (LastName_Account, FirstName_Account, Email_Account, Password_Account, Image_Account, Description_Account, Address_Account, PhoneNumber_Account, Studies_Account, Id_Roles) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Doe', 'John', 'johndoe@example.com', 'hashedpassword', '', '', '', 123456789, '', 1]);
    }

    protected function tearDown(): void {
        // Suppression du compte de test après chaque test
        $this->pdo->exec("DELETE FROM Accounts WHERE Email_Account = 'johndoe@example.com'");
    }

    public function testStoreAccount() {
        // Tentative d'insertion du compte
        $result = $this->account->storeAccount("Doe", "John", "johndoe@example.com", "password123");
        $this->assertTrue($result);

        // Vérifier si le compte a bien été ajouté
        $stmt = $this->pdo->prepare("SELECT * FROM Accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();

        // Validation des informations du compte
        $this->assertNotEmpty($account);
        $this->assertEquals("Doe", $account['LastName_Account']);
        $this->assertEquals("John", $account['FirstName_Account']);
    }

    public function testGetAccountByEmail() {
        // Récupération du compte par email
        $account = $this->account->getAccountByEmail('johndoe@example.com');

        // Validation des informations du compte récupéré
        $this->assertIsArray($account);
        $this->assertEquals('Doe', $account['LastName_Account']);
        $this->assertEquals('John', $account['FirstName_Account']);
        $this->assertEquals('johndoe@example.com', $account['Email_Account']);
    }

    public function testRemoveAccount() {
        // Récupérer l'ID du compte avant la suppression
        $stmt = $this->pdo->prepare("SELECT Id_Account FROM Accounts WHERE Email_Account = ?");
        $stmt->execute(['johndoe@example.com']);
        $account = $stmt->fetch();
    
        $this->assertNotEmpty($account);
        $accountId = $account['Id_Account'];
    
        // Tentative de suppression du compte
        $result = $this->account->removeAccount($accountId);
        $this->assertTrue($result);
    
        // Vérification que le compte a bien été supprimé
        $stmt = $this->pdo->prepare("SELECT * FROM Accounts WHERE Id_Account = ?");
        $stmt->execute([$accountId]);
        $account = $stmt->fetch();
    
        // Vérification que le compte n'existe plus dans la base de données
        $this->assertEmpty($account);
    }
    public function testGetAllAccounts() {
        // Récupération de tous les comptes
        $accounts = $this->account->getAllAccounts();
    
        // Vérification que l'on récupère des comptes
        $this->assertNotEmpty($accounts);
        $this->assertGreaterThan(0, count($accounts)); // Vérifier qu'il y a au moins un compte
        
        // Rechercher le compte de test dans le tableau retourné
        $found = false;
        foreach ($accounts as $account) {
            if ($account['Email_Account'] === 'johndoe@example.com') {
                $found = true;
                break;
            }
        }
        
        // Vérifier que le compte de test est présent dans la liste
        $this->assertTrue($found, "Le compte de test avec l'email 'johndoe@example.com' n'a pas été trouvé");
    }
    
}
?>
