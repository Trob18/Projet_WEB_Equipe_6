<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\Permission;
use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/PermissionModel.php';

class PermissionTest extends TestCase {
    private $permission;
    private $pdo;

    protected function setUp(): void {
        $this->pdo = (new \app\Config\ConfigDatabase())->connect();
        $this->permission = new Permission($this->pdo);
        $this->cleanUpTestPermissions();
    }

    protected function tearDown(): void {
        $this->cleanUpTestPermissions();
    }

    public function testStorePermission() {
        $data = $this->getTestPermissionData();
        $id = $this->permission->StorePermission($data);

        $this->assertGreaterThan(0, $id, "L'ID de la permission insérée doit être supérieur à 0.");
        $permission = $this->fetchPermissionById($id);

        $this->assertNotEmpty($permission, "La permission insérée doit exister dans la base.");
        $this->assertEquals($data['Description_Permission'], $permission['Description_Permission'], "La description de la permission doit correspondre.");
    }

    public function testGetPermission() {
        $id = $this->insertTestPermission();
        $permission = $this->permission->GetPermission($id);

        $this->assertNotEmpty($permission, "La permission récupérée ne doit pas être vide.");
        $this->assertEquals('Permission de test', $permission['Description_Permission'], "La description de la permission récupérée doit être correcte.");
    }

    public function testGetAllPermission() {
        $this->insertTestPermission('Permission 1');
        $this->insertTestPermission('Permission 2');
        $permissions = $this->permission->GetAllPermission();

        $this->assertNotEmpty($permissions, "La liste des permissions ne doit pas être vide.");
        $descriptions = array_column($permissions, 'Description_Permission');

        $this->assertContains('Permission 1', $descriptions, "La liste des permissions doit contenir 'Permission 1'.");
        $this->assertContains('Permission 2', $descriptions, "La liste des permissions doit contenir 'Permission 2'.");
    }

    public function testRemovePermission() {
        $id = $this->insertTestPermission();
        $result = $this->permission->RemovePermission($id);

        $this->assertEquals(1, $result, "La suppression de la permission doit retourner 1.");
        $permission = $this->fetchPermissionById($id);

        $this->assertFalse($permission, "La permission supprimée ne doit plus exister dans la base.");
    }

    public function testEditPermission() {
        $id = $this->insertTestPermission();
        $updatedData = [
            'Description_Permission' => 'Permission mise à jour'
        ];

        $result = $this->permission->EditPermission($id, $updatedData);

        $this->assertEquals(1, $result, "La mise à jour doit retourner 1.");
        $permission = $this->fetchPermissionById($id);

        $this->assertEquals('Permission mise à jour', $permission['Description_Permission'], "La description de la permission mise à jour doit être correcte.");
    }

    public function testRemoveAllPermission() {
        $this->insertTestPermission('Permission 1');
        $this->insertTestPermission('Permission 2');
        
        $result = $this->permission->RemoveAllPermission();
        
        $this->assertEquals(1, $result, "La suppression de toutes les permissions doit retourner 1.");
        
        $permissions = $this->permission->GetAllPermission();
        $this->assertEmpty($permissions, "La liste des permissions doit être vide après suppression.");
    }

    private function getTestPermissionData($description = 'Permission de test') {
        return [
            'Description_Permission' => $description
        ];
    }

    private function insertTestPermission($description = 'Permission de test') {
        $stmt = $this->pdo->prepare("INSERT INTO permissions (Description_Permission) VALUES (?)");
        $stmt->execute([$description]);
        return $this->pdo->lastInsertId();
    }

    private function fetchPermissionById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE Id_Permissions = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function cleanUpTestPermissions() {
        $this->pdo->exec("DELETE FROM permissions WHERE Description_Permission LIKE 'Permission%'");
    }
}
