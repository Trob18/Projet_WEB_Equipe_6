<?php

namespace app\Controller;

use app\Model\CompanyModel;
use PDO;

require_once __DIR__ . '/../Model/CompanyModel.php';


class CompanyController
{
    private $CompanyModel;

    public function __construct(PDO $pdo)
    {
        $this->CompanyModel = new CompanyModel($pdo);
    }


    // Obtenir un compte par email
    public function getCompany($column, $value, $selectColumn = '*')
    {
        $account = $this->CompanyModel->getCompany($column, $value, $selectColumn);
        return $account ? $account : "Company introuvable!";
    }

    public function createCompany($firstName, $email, $address, $description) {
        $result = $this->CompanyModel->storeCompany($firstName, $email, $address, $description);
        return $result;
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
        $account = $this->CompanyModel->getCompany('Id_Company', $IdCompany);
        if (!$account) {
            return "Compte introuvable!";
        }

        $result = $this->CompanyModel->removeCompany($IdCompany);
        return $result ? "Compte supprimé avec succès!" : "Échec de la suppression du compte."; 
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
        $store = $this->CompanyModel->StoreCompany($IdCompany, $NameCompany, $ImageCompany, $EmailCompany);
        if (!$store) {
            return false; // Erreur de création
        }
        return true; // Création réussie
    }

    // Mettre à jour un compte
    public function editCompany($IdCompany, $newData)
    {
        $account = $this->CompanyModel->getCompany('Id_Company', $IdCompany);

        if (!$account) {
            return false; //"Company introuvable!"
        }

        $result = $this->CompanyModel->editCompany($IdCompany, $newData);
        return $result ? "Compte mis à jour avec succès!" : "Échec de la mise à jour du compte.";
    }

    public function getCompaniesWithPagination($limit, $offset)
    {
        return $this->CompanyModel->getCompaniesWithPagination($limit, $offset);
    }

    public function getTotalCompanies()
    {
        return $this->CompanyModel->getTotalCompanies();
    }

    public function searchCompanies($searchName, $searchLocation, $limit, $offset)
    {
        return $this->CompanyModel->searchCompanies($searchName, $searchLocation, $limit, $offset);
    }

    public function showCompanyDetails() {
        if (isset($_GET['id'])) {
            $companyId = $_GET['id'];
            $company = $this->CompanyModel->getCompany('Id_Company', $companyId);
    
            if ($company) {
                echo $this->twig->render('Company_Details.twig', ['companies' => [$company]]);
            } else {
                echo "Entreprise non trouvée.";
            }
        } else {
            echo "Aucune entreprise sélectionnée.";
        }
    }





    public function uploadimg($userId, $imageUrl){
        return $this->CompanyModel->uploadimgC($userId, $imageUrl);
    }
}
