<?php

namespace app\Test;

use PHPUnit\Framework\TestCase;
use app\Model\NotesModel;
use PDO;

require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\config\ConfigDatabase2.php';
require_once 'C:\wamp64\www\Projet_WEB_Equipe_6-main\app\Model\AccountModel.php';

class NotesTest extends TestCase {
    private $notesModel;
    private $pdo;

    // Initialise l'environnement de test avant chaque test
    protected function setUp(): void {
        $configDatabase = new \app\config\ConfigDatabase2nd();
        $this->pdo = $configDatabase->getConnection();
        $this->notesModel = new NotesModel($this->pdo);
        $this->cleanUpTestNotes();
    }

    // Nettoie l'environnement après chaque test
    protected function tearDown(): void {
        $this->cleanUpTestNotes();
    }

    // Teste la création d'une note dans la base de données
    

    // Teste la récupération d'une note spécifique
    public function testGetNote() {
        $id = $this->insertTestNote();
        $note = $this->notesModel->getNote('Id_Notes', $id);

        $this->assertNotEmpty($note, "La note récupérée ne doit pas être vide.");
        $this->assertEquals(4, $note['Note'], "Le contenu de la note récupérée doit être correct.");
    }

    // Teste la suppression d'une note spécifique
    public function testRemoveNote() {
        $id = $this->insertTestNote();
        $result = $this->notesModel->removeNote($id);

        $this->assertTrue($result, "La suppression de la note doit retourner true.");
        $note = $this->fetchNoteById($id);

        $this->assertFalse($note, "La note supprimée ne doit plus exister dans la base.");
    }

    // Teste la modification d'une note existante
    public function testEditNote() {
        $id = $this->insertTestNote();
        $updatedData = [
            'Note' => 5,
            'Comment' => 'Commentaire mis à jour pour test.'
        ];

        $this->notesModel->editNote($id, $updatedData);

        $note = $this->fetchNoteById($id);

        $this->assertEquals(5, $note['Note'], "La note mise à jour doit être correcte.");
        $this->assertEquals('Commentaire mis à jour pour test.', $note['Comment'], "Le commentaire mis à jour doit être correct.");
    }

    // Teste la récupération de toutes les notes
    public function testGetAllNotes() { 
        $this->insertTestNote(3, 'Commentaire 1');
        $this->insertTestNote(5, 'Commentaire 2');
        $notes = $this->notesModel->getAllNotes();

        $this->assertNotEmpty($notes, "La liste des notes ne doit pas être vide.");
        $noteComments = array_column($notes, 'Comment');

        $this->assertContains('Commentaire 1', $noteComments, "La liste des notes doit contenir 'Commentaire 1'.");
        $this->assertContains('Commentaire 2', $noteComments, "La liste des notes doit contenir 'Commentaire 2'.");
    }

    public function testRemoveAllPermission() {
        $this->insertTestNote('Permission 1');
        $this->insertTestNote('Permission 2');
        
        $this->notesModel->removeAllNotes();
        
        $stmt = $this->pdo->prepare("SELECT * FROM notes");
        $stmt->execute();
        $note= $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEmpty($note, "La liste des permissions doit être vide après suppression.");
    }

    // Crée des données de test pour une note
    private function getTestNoteData($noteValue = 4) {
        return [
            'Id_Notes' => 15,
            'Note' => $noteValue,
            'Id_Account'=> 19,
            'Id_Company'=> 3
        ];
    }


    public function testStoreNote() {
        $this->pdo->exec("DELETE FROM notes WHERE Id_Notes = 15");
        $data = $this->getTestNoteData();
        $id = $this->notesModel->storeNote($data);

        $this->assertGreaterThan(0, $id, "L'ID de la note insérée doit être supérieur à 0.");
        $note = $this->fetchNoteById($id);
  

        $this->assertNotEmpty($note, "La note insérée doit exister dans la base.");
        $this->assertEquals($data['Note'], $note['Note'], "La note doit correspondre.");
    }

    // Insère une note de test dans la base de données
    private function insertTestNote($noteValue = 4, $comment = 'Ceci est un commentaire de test.') {
        $stmt = $this->pdo->prepare("INSERT INTO notes (Note, Comment) VALUES (?, ?)");
        $stmt->execute([$noteValue, $comment]);
        return $this->pdo->lastInsertId();
    }

    // Récupère une note par son ID
    private function fetchNoteById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM notes WHERE Id_Notes = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nettoie les notes de test de la base de données
    private function cleanUpTestNotes() {
        $this->pdo->exec("DELETE FROM notes WHERE Comment LIKE 'Ceci est un commentaire de test.%' OR Comment LIKE 'Commentaire%'");
    }
}
