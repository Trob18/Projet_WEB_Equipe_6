<?php

namespace app\Controller;

require_once __DIR__ . '/../Model/NotesModel.php';

use app\Model\NotesModel;
use PDO;

class NotesController {
    private $notesModel;

    public function __construct(PDO $pdo) {
        $this->notesModel = new NotesModel($pdo);
    }

    public function getNote($column, $value, $selectColumn = '*') {
        $note = $this->notesModel->getNote($column, $value, $selectColumn);
        return $note ? $note : "La note n'existe pas !";
    }

    public function getAllNotes() {
        $notes = $this->notesModel->getAllNotes();
        return !empty($notes) ? $notes : "Aucune Note Trouvée !";
    }
    
    public function createNote($newdata) {
        
        $result = $this->notesModel->storeNote($newdata);
        return $result ? "Note Créée !" : "Échec de la création";
    }

    public function removeNote($id) {
        // Vérifier si la note existe
        if (!$this->notesModel->getNote('Id_Notes', $id)) {
            return "Note Introuvable";
        }
        
        $result = $this->notesModel->removeNote($id);
        return $result ? "Note Supprimée" : "Échec de la suppression";
    }

    public function removeAllNotes() {
        $result = $this->notesModel->removeAllNotes();
        return $result ? "Toutes les notes sont supprimées" : "Note(s) Introuvable(s)";
    }

    public function editNote($id, $newdata) {
        // Vérifier si la note existe
        if (!$this->notesModel->getNote('Id_Notes', $id)) {
            return "Note Introuvable";
        }
        
        $requiredFields = ['Note', 'Comment'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        
        $result = $this->notesModel->editNote($id, $newdata);
        return $result ? "Modification Réussie !" : "Échec de la Modification";
    }
    public function getAllNotesArg($argument) {
        $notes = $this->notesModel->getAllNotesArg($argument);
        return !empty($notes) ? $notes : "Aucune Note Trouvée !";
    }
}