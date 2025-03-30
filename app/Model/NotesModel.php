<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class NotesModel {
    private $pdo;

    /**
     * Constructeur de la classe NotesModel
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * Récupère une note en fonction d'une colonne et d'une valeur spécifiques
     * 
     * @param string $column Nom de la colonne pour la condition
     * @param mixed $value Valeur à rechercher
     * @param string $selectColumn Colonne(s) à sélectionner (par défaut toutes '*')
     * @return mixed Résultat de la requête ou message d'erreur
     */
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

    /**
     * Récupère toutes les notes
     * 
     * @return array Liste de toutes les notes
     */
    public function getAllNotes(){
        $stmt = $this->pdo->prepare("SELECT * FROM notes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle note
     * 
     * @param array $newdata Données de la nouvelle note
     * @return int|bool Identifiant de la note créée ou false en cas d'échec
     */
    public function storeNote($newdata){
        $stmt = $this->pdo->prepare("INSERT INTO notes (Note, Comment) VALUES (?, ?)");
        $result = $stmt->execute([$newdata['Note'], $newdata['Comment']]);
        return $result ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Supprime une note spécifique
     * 
     * @param int $id Identifiant de la note à supprimer
     * @return bool Résultat de l'opération
     */
    public function removeNote($id){
        $stmt = $this->pdo->prepare("DELETE FROM notes WHERE Id_Notes = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Supprime toutes les notes
     * 
     * @return bool Résultat de l'opération
     */
    public function removeAllNotes(){
        $stmt = $this->pdo->prepare("DELETE FROM notes");
        return $stmt->execute();
    }
    
    /**
     * Modifie une note existante
     * 
     * @param int $id Identifiant de la note à modifier
     * @param array $newdata Nouvelles données de la note
     * @return bool Résultat de l'opération
     */
    public function editNote($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE notes SET Note = ?, Comment = ? WHERE Id_Notes = ?");
        return $stmt->execute([$newdata['Note'], $newdata['Comment'], $id]);
    }
}
?>
