<?php

namespace app\Test;

use app\Model\PermissionModel;
use PHPUnit\Framework\TestCase;
use app\Model\Permission;
use PDO;

require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\config\ConfigDatabase2.php';
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\app\Model\AccountModel.php';

class PermissionTest extends TestCase {
    private $permission;
    private $pdo;

    // Initialise l'environnement de test avant chaque test
    protected function setUp(): void {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $this->pdo = $configDatabase->getConnection();
        $this->permission = new PermissionModel($this->pdo);
        $this->cleanUpTestPermissions();
    }

    // Nettoie l'environnement après chaque test
    protected function tearDown(): void {
        $this->cleanUpTestPermissions();
    }

    // Teste la création d'une permission dans la base de données
    public function testStorePermission() {
        $data = $this->getTestPermissionData();
        $id = $this->permission->StorePermission($data);

        
        $permission = $this->fetchPermissionById($id);

        
        $this->assertEquals($data['Description_Permission'], $permission['Description_Permission'], "La description de la permission doit correspondre.");
    }

    // Teste la récupération d'une permission spécifique
    public function testGetPermission() {
        $id = $this->insertTestPermission();
        $permission = $this->permission->GetPermission('Id_Permissions',$id);

        
        $this->assertEquals('Permission de test', $permission['Description_Permission'], "La description de la permission récupérée doit être correcte.");
    }

    // Teste la récupération de toutes les permissions
    public function testGetAllPermission() {
        $this->insertTestPermission('Permission 1');
        $this->insertTestPermission('Permission 2');
        $permissions = $this->permission->GetAllPermission();

        $this->assertNotEmpty($permissions, "La liste des permissions ne doit pas être vide.");
        $descriptions = array_column($permissions, 'Description_Permission');

        $this->assertContains('Permission 1', $descriptions, "La liste des permissions doit contenir 'Permission 1'.");
        $this->assertContains('Permission 2', $descriptions, "La liste des permissions doit contenir 'Permission 2'.");
    }

    // Teste la suppression d'une permission spécifique
    public function testRemovePermission() {
        $id = $this->insertTestPermission();
        $result = $this->permission->RemovePermission($id);

        $this->assertEquals(1, $result, "La suppression de la permission doit retourner 1.");
        $permission = $this->fetchPermissionById($id);

        $this->assertFalse($permission, "La permission supprimée ne doit plus exister dans la base.");
    }

    // Teste la modification d'une permission existante
    public function testEditPermission() {
        $id = $this->insertTestPermission();
        $updatedData = [
            'Description_Permission' => 'Permission mise à jour'
        ];

        $this->permission->EditPermission($id, $updatedData);

        $permission = $this->fetchPermissionById($id);

        $this->assertEquals('Permission mise à jour', $permission['Description_Permission']);
    }

    // Teste la suppression de toutes les permissions
    public function testRemoveAllPermission() {
        $this->insertTestPermission('Permission 1');
        $this->insertTestPermission('Permission 2');
        
        $this->permission->RemoveAllPermission();
        
        $stmt = $this->pdo->prepare("SELECT * FROM permissions");
        $stmt->execute();
        $permissions= $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEmpty($permissions, "La liste des permissions doit être vide après suppression.");
    }

    // Crée des données de test pour une permission
    private function getTestPermissionData($description = 'Permission de test') {
        return [
            'Description_Permission' => $description
        ];
    }

    // Insère une permission de test dans la base de données
    private function insertTestPermission($description = 'Permission de test') {
        $stmt = $this->pdo->prepare("INSERT INTO permissions (Description_Permission) VALUES (?)");
        $stmt->execute([$description]);
        return $this->pdo->lastInsertId();
    }

    // Récupère une permission par son ID
    private function fetchPermissionById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE Id_Permissions = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nettoie les permissions de test de la base de données
    private function cleanUpTestPermissions() {
        $this->pdo->exec("DELETE FROM permissions WHERE Description_Permission LIKE 'Permission%'");
    }
}