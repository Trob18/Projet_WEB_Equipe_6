<?php

namespace app\Controller;


require_once __DIR__ . '/../Model/AccountModel.php';
use app\Model\AccountModel;
use PDO;


class AccountController {
    private $accountModel;
    
    public function __construct(PDO $pdo) {
        $this->accountModel = new AccountModel($pdo);
    }


    public function createAccount($lastName, $firstName, $dateNaissance, $email, $telephone, $password, $type) {
        if ($this->accountModel->getAccount('Email_Account', $email)) {
            return "Email déjà utilisé!";
        }
        
        $result = $this->accountModel->storeAccount($lastName, $firstName, $dateNaissance, $email, $telephone, $password, $type);
        return $result;
    }

    public function getAccount($column, $value, $selectColumn = '*') {
        $account = $this->accountModel->getAccount($column, $value, $selectColumn);
        return $account ? $account : "Compte introuvable!";
    }


    public function removeAccount($accountId) {
        if (!$this->accountModel->getAccount('Id_Account', $accountId)) {
            return "Compte introuvable!";
        }

        $result = $this->accountModel->removeAccount($accountId);
        return $result ? "Compte supprimé avec succès!" : "Échec de la suppression du compte.";
    }

    public function editAccount($accountId, $newData) {
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
