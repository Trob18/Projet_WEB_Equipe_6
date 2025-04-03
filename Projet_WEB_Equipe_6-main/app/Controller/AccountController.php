<?php

namespace app\Controller;


require_once __DIR__ . '/../Model/AccountModel.php';
use app\Model\AccountModel;
use PDO;


class AccountController {
    private $accountModel;
    
    public function __construct(PDO $pdo) {
        $this->accountModel = new AccountModel($pdo); // Initialisation correcte
    }

    // Créer un compte
    public function createAccount($lastName, $firstName, $dateNaissance, $email, $telephone, $password, $type) {
        // Vérifier si l'email existe déjà
        if ($this->accountModel->getAccount('Email_Account', $email)) {
            return "Email déjà utilisé!";
        }
        
        // Créer le compte
        $result = $this->accountModel->storeAccount($lastName, $firstName, $dateNaissance, $email, $telephone, $password, $type);
        return $result;
    }

    // Obtenir un compte par une colonne spécifique
    public function getAccount($column, $value, $selectColumn = '*') {
        // Correction de la syntaxe de l'appel à getAccount
        $account = $this->accountModel->getAccount($column, $value, $selectColumn);
        return $account ? $account : "Compte introuvable!";
    }

    // Supprimer un compte par ID
    public function removeAccount($accountId) {
        // Vérifier si le compte existe
        if (!$this->accountModel->getAccount('Id_Account', $accountId)) {
            return "Compte introuvable!";
        }

        $result = $this->accountModel->removeAccount($accountId);
        return $result ? "Compte supprimé avec succès!" : "Échec de la suppression du compte.";
    }

    // Mettre à jour un compte
    public function editAccount($accountId, $newData) {
        // Vérifier si le compte existe
        if (!$this->accountModel->getAccount('Id_Account', $accountId)) {
            return "Compte introuvable!";
        }

        $result = $this->accountModel->editAccount($accountId, $newData);
        return $result ? "Compte mis à jour avec succès!" : "Échec de la mise à jour du compte.";
    }




    public function getAllAccounts() {
        return $this->accountModel->getAllAccounts();
    }


    public function searchAccounts($searchName, $limit, $offset)
    {
        return $this->accountModel->searchAccounts($searchName, $limit, $offset);
    }


    

    public function getTotalAccount(){
        return $this->accountModel->getTotalAccount();
    }



    public function getAccountWithPagination($limit, $offset)
    {
        return $this->accountModel->getAccountWithPagination($limit, $offset);
    }












    public function uploadimg($userId, $imageUrl){
        return $this->accountModel->uploadimg($userId, $imageUrl);
    }
}
