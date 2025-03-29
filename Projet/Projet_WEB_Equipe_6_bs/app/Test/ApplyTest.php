<?php

namespace app\Test;


use PHPUnit\Framework\TestCase;
use app\Model\ApplyModel;
use PDO;
require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/ApplyModel.php';

class ApplyTest extends TestCase {
    private $apply;
    private $pdo;
    private $applyController;

    protected function setUp(): void {
        $this->pdo = require __DIR__ . '/../../config/ConfigDatabase.php'; 
        $this->apply = new ApplyModel($this->pdo);

        // Suppression du compte de test s'il existe déjà
        $this->pdo->exec("DELETE FROM applications WHERE Id_Application = 1 ");

        // Insertion du compte de test
        $stmt = $this->pdo->prepare("INSERT INTO applications 
            (Id_Application, Cv_Application, CoverLetter_Application,Date_Application) 
            VALUES (?, ?, ?, ?)");
        $stmt->execute([1, 'test.pdf', 'Perdu', 2025-02-20]);
    }

    protected function tearDown(): void {
        // Suppression du compte de test après chaque test
        $this->pdo->exec("DELETE FROM applications WHERE Id_Account = 1");
    }
    public function testStoreApply() {
        // Tentative d'insertion du compte
        $result = $this->apply->storeApply(14, "test", "Test124", "2025-02-20");
        $this->assertTrue($result);

        // Vérifier si le compte a bien été ajouté
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE Id_Application = ?");
        $stmt->execute([0]);
        $apply = $stmt->fetch();

        // Validation des informations du compte
        $this->assertNotEmpty($apply);
        $this->assertEquals(1, $apply['Id_Application']);
        $this->assertEquals("test", $apply['Cv_Application']);
    }

    public function testGetApply() {
        // Récupération du compte par email
        $apply = $this->apply->getApplyById(1);

        // Validation des informations du compte récupéré
        $this->assertIsArray($apply);
        $this->assertEquals('test.pdf', $apply['Cv_Application']);
        $this->assertEquals('Perdu', $apply['CoverLetter_Application']);
        $this->assertEquals("0000-00-00 00:00:00", $apply['Date_Application']);
    }

    public function testRemoveApply() {
        // Récupérer l'ID du compte avant la suppression
        $stmt = $this->pdo->prepare("SELECT Id_Application FROM applications WHERE Id_Application = ?");
        $stmt->execute([1]);
        $apply = $stmt->fetch();
    
        $this->assertNotEmpty($apply);
        $applyId = $apply['Id_Application'];
    
        // Tentative de suppression du compte
        $result = $this->apply->removeApply($applyId);
        $this->assertTrue($result);
    
        // Vérification que le compte a bien été supprimé
        $stmt = $this->pdo->prepare("SELECT * FROM applications WHERE Id_Application = ?");
        $stmt->execute([$applyId]);
        $apply = $stmt->fetch();
    
        // Vérification que le compte n'existe plus dans la base de données
        $this->assertEmpty($apply);
    }

    public function testGetAllApply() {
        // Récupération de tous les comptes
        $apply = $this->apply->getAllApply();

        // Vérification que l'on récupère bien le compte de test
        $this->assertNotEmpty($apply);
        $this->assertGreaterThan(0, count($apply)); // Vérifier qu'il y a au moins un compte
        $this->assertEquals(2, $apply[0]['Id_Application']);
    }
}
?>