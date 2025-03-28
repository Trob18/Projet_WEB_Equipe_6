<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class Permission{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function GetPermission($id){
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE Id_Permissions = :id");
        $stmt ->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public function EditPermission($id,$newdata){
        $stmt = $this->pdo->prepare("UPDATE permissions SET Description_Permission = ? WHERE Id_Permissions = ?");
        return $stmt->execute([$newdata['Description_Permission'],$id]);
    }
}

?>