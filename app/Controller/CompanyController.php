<?php

namespace app\Controller;

use app\Model\CompanyModel;
use PDO;

require_once __DIR__ . '/../Model/CompanyModel.php';


class CompanyController
{
    private $CompanyModel;

    public function __construct($pdo)
    {
        $this->CompanyModel = new CompanyModel($pdo);
    }


    // Obtenir un compte par email
    public function getCompany($column, $value, $selectColumn = '*')
    {
        $account = $this->CompanyModel->getCompany($column, $value, $selectColumn);
        return $account ? $account : "Company introuvable!";
    }

    // Récupérer tous les comptes
    public function getAllCompany()
    {
        $accounts = $this->CompanyModel->getAllCompany();
        return $accounts ? $accounts : "Aucun Company trouvé.";
    }

    // Supprimer un compte par ID
    public function removeCompany($IdCompany)
    {
        $account = $this->CompanyModel->getCompanyById($IdCompany);
        if (!$account) {
            return false; //"Company introuvable!"
        }

        $result = $this->CompanyModel->removeCompany($IdCompany);
        return $result ? true : false; //"Company supprimé avec succès!" : "Échec de la suppression du Company."
    }

    // Supprimer tous les compte par ID
    public function removeAllCompany($IdCompany)
    {
        $account = $this->CompanyModel->getAllCompany();
        if (!$account) {
            return false; //"Company introuvable!"
        }

        $result = $this->CompanyModel->removeAllCompany();
        return $result ? true : false; //"Company supprimé avec succès!" : "Échec de la suppression du Company."
    }

    public function storeCompany($IdCompany, $NameCompany, $ImageCompany, $EmailCompany, $AdresseCompany, $DescriptionCompany)
    {
        $store = $this->CompanyModel->StoreCompany($IdCompany, $NameCompany, $ImageCompany, $EmailCompany, $AdresseCompany, $DescriptionCompany);
        if (!$store) {
            return false; // Erreur de création
        }
        return true; // Création réussie
    }

    // Mettre à jour un compte
    public function editCompany($IdCompany, $newData)
    {
        $account = $this->CompanyModel->getCompanyById($IdCompany);
        if (!$account) {
            return false; //"Company introuvable!"
        }

        $result = $this->CompanyModel->editCompany($IdCompany, $newData);
        return $result ? true : false; //"Company mis à jour avec succès!" : "Échec de la mise à jour du Company."
    }


}
?>