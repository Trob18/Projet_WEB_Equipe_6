<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class PermissionModel{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function GetPermission($column, $value, $selectColumn = '*'){
        $validColumns = [
            'Id_Permissions', 'Description_Permission'
        ];
    
        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }
    
        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM Permissions WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }
    
        return $result ?: null;
    }
    

    public function GetAllPermission(){
        $stmt = $this->pdo->prepare("SELECT * FROM permissions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function StorePermission($newdata){
        $stmt = $this->pdo->prepare("INSERT INTO permissions (Description_Permission) VALUES (?)");
        $stmt->execute([$newdata["Description_Permission"]]);
        return $this->pdo->lastInsertID();
    }

    public function RemovePermission($id){
        $stmt = $this->pdo->prepare("DELETE FROM permissions WHERE Id_Permissions = :id");
        return $stmt->execute(["id" => $id]);
    }

    public function RemoveAllPermission(){
        $stmt = $this->pdo->prepare("DELETE FROM permissions");
        return $stmt->execute();
    }

    public function EditPermission($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE permissions SET Description_Permission = ? WHERE Id_Permissions = ?");
        return $stmt->execute([$newdata['Description_Permission'], $id]);
    }
}

?>
