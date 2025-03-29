<?php
namespace app\Controller;

/**
 * Inclusion des fichiers nécessaires
 */
require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/PermissionModel.php';

/**
 * Classe PermissionController
 * 
 * Cette classe gère les opérations CRUD pour les permissions
 * Elle fait le lien entre les requêtes utilisateur et le modèle de données
 */
class PermissionController{
    /**
     * Instance du modèle de permission
     * @var PermissionModel
     */
    private $permissionModel;

    /**
     * Constructeur de la classe
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->permissionModel = new PermissionModel($pdo);
    }
    
    /**
     * Récupère une permission par son identifiant
     * 
     * @param int $id Identifiant de la permission
     * @return mixed Données de la permission ou message d'erreur
     */
    public function GetPermission($id){
        $permission = $this->permissionModel->GetPermission($id);
        if (!$permission){
            return "La permission n'existe pas !";
        }
        else{
            return $permission;
        }
    }
    
    /**
     * Récupère toutes les permissions
     * 
     * @return mixed Liste des permissions ou message d'erreur
     */
    public function GetAllPermission(){
        $permissions = $this->permissionModel->GetAllPermission();
        if (!$permissions){
            return "La/Les permission(s) n'existe(nt) pas !";
        }
        else{
            return $permissions;
        }
    }
    
    /**
     * Crée une nouvelle permission
     * 
     * @param array $newdata Données de la nouvelle permission
     * @return string Message de confirmation ou d'erreur
     */
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

    /**
     * Supprime une permission par son identifiant
     * 
     * @param int $id Identifiant de la permission à supprimer
     * @return string Message de confirmation ou d'erreur
     */
    public function RemovePermission($id){
        $permission = $this->permissionModel->RemovePermission($id);
        if (!$permission){
            return "Permission Introuvable";
        }
        else{
            return "Permission Supprimée";
        }
    }

    /**
     * Supprime toutes les permissions
     * 
     * @return string Message de confirmation ou d'erreur
     */
    public function RemoveAllPermission(){
        $permissions = $this->permissionModel->RemoveAllPermission();
        if (!$permissions){
            return "Permission(s) Introuvable(s)";
        }
        else{
            return "Toutes les permissions sont supprimées";
        }
    }
    
    /**
     * Modifie une permission existante
     * 
     * @param int $id Identifiant de la permission à modifier
     * @param array $newdata Nouvelles données de la permission
     * @return string Message de confirmation ou d'erreur
     */
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
