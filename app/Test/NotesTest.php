<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\Notes;
use PDO;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/NotesModel.php';

class NotesTest extends TestCase {
    private $notes;
    private $pdo;

    protected function setUp(): void {
        $this->pdo = (new \app\Config\ConfigDatabase())->connect();
        $this->notes = new Notes($this->pdo);
        $this->cleanUpTestNotes();
    }

    protected function tearDown(): void {
        $this->cleanUpTestNotes();
    }

    // Teste la création d'une note dans la base de données
    public function testStoreNotes() {
        $data = [
            'Note' => 4,
            'Comment' => 'TEST_Comment_1'
        ];
        
        $id = $this->notes->StoreNotes($data);
        
        $this->assertGreaterThan(0, $id, "L'ID de la note insérée doit être supérieur à 0.");
        
        $note = $this->notes->GetNotes($id);
        
        $this->assertNotEmpty($note, "La note insérée doit exister dans la base.");
        $this->assertEquals($data['Note'], $note['Note'], "La note numérique doit correspondre.");
        $this->assertEquals($data['Comment'], $note['Comment'], "Le commentaire doit correspondre.");
    }

    // Teste la récupération d'une note spécifique
    public function testGetNotes() {
        $data = [
            'Note' => 3,
            'Comment' => 'TEST_Comment_1'
        ];
        
        $id = $this->notes->StoreNotes($data);
        
        $note = $this->notes->GetNotes($id);
        
        $this->assertNotEmpty($note, "La note récupérée ne doit pas être vide.");
        $this->assertEquals(3, $note['Note'], "La note numérique récupérée doit être correcte.");
        $this->assertEquals('TEST_Comment_1', $note['Comment'], "Le commentaire récupéré doit être correct.");
    }

    // Teste la suppression d'une note spécifique
    public function testRemoveNotes() {
        $data = [
            'Note' => 2,
            'Comment' => 'TEST_Comment_1'
        ];
        
        $id = $this->notes->StoreNotes($data);
        
        $result = $this->notes->RemoveNotes($id);
        
        $this->assertTrue($result, "La suppression de la note doit retourner true.");
        
        $note = $this->notes->GetNotes($id);
        $this->assertEmpty($note, "La note supprimée ne doit plus exister dans la base.");
    }

    // Teste la modification d'une note existante
    public function testEditNotes() {
        $data = [
            'Note' => 1,
            'Comment' => 'TEST_Comment_1'
        ];
        
        $id = $this->notes->StoreNotes($data);
        
        $updatedData = [
            'Note' => 5,
            'Comment' => 'TEST_Comment_Updated'
        ];
        
        $result = $this->notes->EditNotes($id, $updatedData);
        
        $this->assertTrue($result, "La mise à jour doit retourner true.");
        
        $note = $this->notes->GetNotes($id);
        $this->assertEquals(5, $note['Note'], "La note numérique mise à jour doit être correcte.");
        $this->assertEquals('TEST_Comment_Updated', $note['Comment'], "Le commentaire mis à jour doit être correct.");
    }

    // Teste la récupération de toutes les notes
    public function testGetAllNotes() {
        $this->cleanUpTestNotes();
        
        $data1 = [
            'Note' => 4,
            'Comment' => 'TEST_Comment_1'
        ];
        $id1 = $this->notes->StoreNotes($data1);
        
        $data2 = [
            'Note' => 5,
            'Comment' => 'TEST_Comment_2'
        ];
        $id2 = $this->notes->StoreNotes($data2);
        
        $allNotes = $this->notes->GetAllNotes();
        
        $this->assertNotEmpty($allNotes, "La liste des notes ne doit pas être vide.");
        
        $foundNote1 = false;
        $foundNote2 = false;
        
        foreach ($allNotes as $note) {
            if ($note['Id_Notes'] == $id1 && $note['Note'] == 4 && $note['Comment'] == 'TEST_Comment_1') {
                $foundNote1 = true;
            }
            if ($note['Id_Notes'] == $id2 && $note['Note'] == 5 && $note['Comment'] == 'TEST_Comment_2') {
                $foundNote2 = true;
            }
        }
        
        $this->assertTrue($foundNote1, "La première note de test doit être présente dans la liste.");
        $this->assertTrue($foundNote2, "La deuxième note de test doit être présente dans la liste.");
    }

    // Teste la suppression de toutes les notes
    public function testRemoveAllNotes() {
        $data1 = [
            'Note' => 3,
            'Comment' => 'TEST_Comment_1'
        ];
        $this->notes->StoreNotes($data1);
        
        $data2 = [
            'Note' => 2,
            'Comment' => 'TEST_Comment_2'
        ];
        $this->notes->StoreNotes($data2);
        
        $notesBefore = $this->notes->GetAllNotes();
        $this->assertNotEmpty($notesBefore, "Il devrait y avoir des notes avant la suppression.");
        
        $result = $this->notes->RemoveAllNotes();
        
        $this->assertTrue($result, "La suppression de toutes les notes doit retourner true.");
        
        $notesAfter = $this->notes->GetAllNotes();
        $this->assertEmpty($notesAfter, "La liste des notes doit être vide après suppression de toutes les notes.");
    }

    // Nettoie les notes de test de la base de données
    private function cleanUpTestNotes() {
        $stmt = $this->pdo->prepare("DELETE FROM notes WHERE Comment LIKE 'TEST\\_%'");
        $stmt->execute();
    }
}
