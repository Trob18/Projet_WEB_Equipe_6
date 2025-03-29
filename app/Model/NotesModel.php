<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class Notes {
    private $pdo;

    /**
     * Constructeur de la classe Notes
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * Récupère une note spécifique par son identifiant
     * 
     * @param int $id Identifiant de la note
     * @return array|false Données de la note ou false si non trouvée
     */
    public function GetNotes($id){
        $stmt = $this->pdo->prepare("SELECT * FROM notes WHERE Id_Notes = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les notes
     * 
     * @return array Liste de toutes les notes
     */
    public function GetAllNotes(){
        $stmt = $this->pdo->prepare("SELECT * FROM notes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle note
     * 
     * @param array $newdata Données de la nouvelle note
     * @return int Identifiant de la note créée
     */
    public function StoreNotes($newdata){
        $stmt = $this->pdo->prepare("INSERT INTO notes (Note, Comment) VALUES (?, ?)");
        $stmt->execute([$newdata['Note'], $newdata['Comment']]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Supprime une note spécifique
     * 
     * @param int $id Identifiant de la note à supprimer
     * @return bool Résultat de l'opération
     */
    public function RemoveNotes($id){
        $stmt = $this->pdo->prepare("DELETE FROM notes WHERE Id_Notes = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Supprime toutes les notes
     * 
     * @return bool Résultat de l'opération
     */
    public function RemoveAllNotes(){
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
    public function EditNotes($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE notes SET Note = ?, Comment = ? WHERE Id_Notes = ?");
        return $stmt->execute([$newdata['Note'], $newdata['Comment'], $id]);
    }
}
?>
