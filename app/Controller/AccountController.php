<?php

namespace app\Model;


require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/AccountModel.php';

class AccountController {
    private $accountModel;

    public function __construct($pdo) {
        $this->accountModel = new Account($pdo);
    }

    // Créer un compte
    public function createAccount($lastName, $firstName, $email, $password) {
        // Vérifier si l'email existe déjà
        if ($this->accountModel->getAccount('Email_Account', $email)) {
            return "Email déjà utilisé!";
        }
        
        // Créer le compte
        $result = $this->accountModel->storeAccount($lastName, $firstName, $email, $password);
        return $result ? "Compte créé avec succès!" : "Échec de la création du compte.";
    }

    // Obtenir un compte par email
    public function getAccount($email) {
        // Utilisation de get_account pour obtenir un compte par email
        $account = $this->accountModel->getAccount('Email_Account', $email);
        return $account ? $account : "Compte introuvable!";
    }

    // Supprimer un compte par ID
    public function removeAccount($accountId) {
        // Utilisation de get_account pour vérifier si le compte existe
        $account = $this->accountModel->getAccount('Id_Account', $accountId);
        if (!$account) {
            return "Compte introuvable!";
        }

        $result = $this->accountModel->removeAccount($accountId);
        return $result ? "Compte supprimé avec succès!" : "Échec de la suppression du compte.";
    }

    // Mettre à jour un compte
    public function editAccount($accountId, $newLastName, $newFirstName, $newEmail, $newPassword) {
        // Utilisation de get_account pour vérifier si le compte existe
        $account = $this->accountModel->getAccount('Id_Account', $accountId);
        if (!$account) {
            return "Compte introuvable!";
        }

        $result = $this->accountModel->editAccount($accountId, $newLastName, $newFirstName, $newEmail, $newPassword);
        return $result ? "Compte mis à jour avec succès!" : "Échec de la mise à jour du compte.";
    }

    // Récupérer tous les comptes
    public function getAllAccounts() {
        $accounts = $this->accountModel->getAllAccounts();
        return $accounts ? $accounts : "Aucun compte trouvé.";
    }
}
?>
