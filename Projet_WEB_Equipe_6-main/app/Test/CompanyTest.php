<?php

namespace app\Test;


use PHPUnit\Framework\TestCase;
use app\Model\CompanyModel;
use PDO;
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\config\ConfigDatabase2.php';
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\app\Model\AccountModel.php';

class CompanyTest extends TestCase
{
    private $company;
    private $pdo;
    private $companyController;

    protected function setUp(): void
    {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $this->pdo = $configDatabase->getConnection();
        $this->company = new CompanyModel($this->pdo);

        // Suppression du compte de test s'il existe déjà
        $this->pdo->exec("DELETE FROM companies WHERE Id_Company = 10 ");

        // Insertion du compte de test
        $stmt = $this->pdo->prepare("INSERT INTO companies 
            (Id_Company,Name_Company,Image_Company,Email_Company,Address_Company,Description_Company) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([10, "company", "imagetest.jpg", "e-mail.test", "27 rue des tests, 44000 Groland", "test_compte_test"]);
    }

    protected function tearDown(): void
    {
        // Suppression du compte de test après chaque test
        $this->pdo->exec("DELETE FROM companies WHERE Id_Company = 1");
    }
    public function testStoreApply()
    {
        $this->pdo->exec("DELETE FROM companies WHERE Id_Company = 15");

        // Tentative d'insertion du compte
        $result = $this->company->storeCompany(15, "company", "imagetest.jpg", "e-mail.test", "27 rue des tests, 44000 Groland", "test_insert_into");
        $this->assertTrue($result);

        // Vérifier si le compte a bien été ajouté
        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE Id_Company = ?");
        $stmt->execute([15]);
        $company = $stmt->fetch();

        // Validation des informations du compte
        $this->assertNotEmpty($company);
        $this->assertEquals(15, $company['Id_Company']);
        $this->assertEquals("e-mail.test", $company['Email_Company']);
    }

    public function testGetApply()
    {
        // Récupération du compte par email
        $company = $this->company->getCompany('Id_Company',10);

        // Validation des informations du compte récupéré
        $this->assertIsArray($company);
        $this->assertEquals(10, $company['Id_Company']);
        $this->assertEquals('27 rue des tests, 44000 Groland', $company['Address_Company']);
        $this->assertEquals("imagetest.jpg", $company['Image_Company']);
    }
    public function testRemoveApply()
    {
        // Récupérer l'ID du compte avant la suppression
        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE Id_Company = ?");
        $stmt->execute([10]);
        $company = $stmt->fetch();


        $this->assertNotEmpty($company);
        $companyId = $company['Id_Company'];

        // Tentative de suppression du compte
        $result = $this->company->removeCompany($companyId);
        $this->assertTrue($result);

        // Vérification que le compte a bien été supprimé
        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE Id_Company = ?");
        $stmt->execute([$companyId]);
        $company = $stmt->fetch();

        // Vérification que le compte n'existe plus dans la base de données
        $this->assertEmpty($company);
    }

    public function testGetAllCompany()
    {
        // Récupération de tous les comptes
        $company = $this->company->getAllCompany();

        // Vérification que l'on récupère bien le compte de test
        $this->assertNotEmpty($company);
        $this->assertGreaterThan(0, count($company)); // Vérifier qu'il y a au moins un compte
        $this->assertEquals(3, $company[0]['Id_Company']); // Vérifier que le premier compte a l'ID 2
    }

    public function testEditCompany() {
        
        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE Id_Company = 10");
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $updatedData = [
            'Name_Company' => 'Test_Edit'
        ];

        $this->company->editCompany($id["Id_Company"], $updatedData);

        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE Id_Company = 10");
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Test_Edit', $id['Name_Company']);
    }
}
?>