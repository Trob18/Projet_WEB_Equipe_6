<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class NotesModel {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getNote($column, $value, $selectColumn = '*') {
        // Liste des colonnes valides pour éviter l'injection SQL
        $validColumns = ['Id_Notes', 'Note', 'Comment'];

        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }

        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM notes WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }

        return $result ?: null;
    }

    public function getAllNotes(){
        $stmt = $this->pdo->prepare("SELECT * FROM notes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeNote($newdata){
        $stmt = $this->pdo->prepare("INSERT INTO notes (Note, Comment) VALUES (?, ?)");
        $result = $stmt->execute([$newdata['Note'], $newdata['Comment']]);
        return $result ? $this->pdo->lastInsertId() : false;
    }

    public function removeNote($id){
        $stmt = $this->pdo->prepare("DELETE FROM notes WHERE Id_Notes = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function removeAllNotes(){
        $stmt = $this->pdo->prepare("DELETE FROM notes");
        return $stmt->execute();
    }
    
    public function editNote($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE notes SET Note = ?, Comment = ? WHERE Id_Notes = ?");
        return $stmt->execute([$newdata['Note'], $newdata['Comment'], $id]);
    }
    public function getAllNotesArg($argument){
        $stmt = $this->pdo->prepare("SELECT * FROM notes WHERE $argument ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
