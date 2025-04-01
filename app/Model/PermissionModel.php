<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class Permission{
    private $pdo;

    /**
     * Constructeur de la classe Permission
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * Récupère une permission spécifique par son identifiant
     * 
     * @param int $id Identifiant de la permission
     * @return array|false Données de la permission ou false si non trouvée
     */
    public function GetPermission($id){
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE Id_Permissions = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les permissions
     * 
     * @return array Liste de toutes les permissions
     */
    public function GetAllPermission(){
        $stmt = $this->pdo->prepare("SELECT * FROM permissions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle permission
     * 
     * @param array $newdata Données de la nouvelle permission
     * @return int Identifiant de la permission créée
     */
    public function StorePermission($newdata){
        $stmt = $this->pdo->prepare("INSERT INTO permissions (Description_Permission) VALUES (?)");
        $stmt->execute([$newdata["Description_Permission"]]);
        return $this->pdo->lastInsertID();
    }

    /**
     * Supprime une permission spécifique
     * 
     * @param int $id Identifiant de la permission à supprimer
     * @return bool Résultat de l'opération
     */
    public function RemovePermission($id){
        $stmt = $this->pdo->prepare("DELETE FROM permissions WHERE Id_Permissions = :id");
        return $stmt->execute(["id" => $id]);
    }

    /**
     * Supprime toutes les permissions
     * 
     * @return bool Résultat de l'opération
     */
    public function RemoveAllPermission(){
        $stmt = $this->pdo->prepare("DELETE FROM permissions");
        return $stmt->execute();
    }

    /**
     * Modifie une permission existante
     * 
     * @param int $id Identifiant de la permission à modifier
     * @param array $newdata Nouvelles données de la permission
     * @return bool Résultat de l'opération
     */
    public function EditPermission($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE permissions SET Description_Permission = ? WHERE Id_Permissions = ?");
        return $stmt->execute([$newdata['Description_Permission'], $id]);
    }
}

?>
