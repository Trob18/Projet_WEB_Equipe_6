<?php
namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/PermissionModel.php';


class PermissionController{
    private $permissionModel;

    public function __construct($pdo){
        $this->permissionModel = new PermissionModel($pdo);
    }
    public function GetPermission($id){
        $permission = $this->permissionModel->GetPermission($id);
        if (!$permission){
            return "La permission n'existe pas !";
        }
        else{
            return $permission;
        }
    }
    public function GetAllPermission(){
        $permissions = $this->permissionModel->GetAllPermission();
        if (!$permissions){
            return "La/Les permission(s) n'existe(nt) pas !";
        }
        else{
            return $permissions;
        }
    }
    public function CreatePermission($newdata){
        if (empty($newdata['Description_Permission'])){
            return "contenu non complété !";
        }
        $permission = $this->permissionModel->StorePermission($newdata);
        if (!$permission){
            return "echec de création";
        }
        else{
            return "Permission Créée !";
        }
    }

    public function RemovePermission($id){
        $permission = $this->permissionModel->RemovePermission($id);
        if (!$permission){
            return "Permission Introuvable";
        }
        else{
            return "Permission Supprimée";
        }
    }

    public function RemoveAllPermission(){
        $permissions = $this->permissionModel->RemoveAllPermission();
        if (!$permissions){
            return "Permission(s) Introuvable(s)";
        }
        else{
            return "Toutes les permissions sont supprimées";
        }
    }
    public function EditPermission($id,$newdata){
        if (empty($newdata['Description_Permission'])){
            return "contenu non complété !";
        }
        $permission = $this->permissionModel->EditPermission($id,$newdata);
        if (!$permission){
            return "Echec de la Modification";
        }
        else{
            return "Modification Reussie !";

        }
    }
}

?>