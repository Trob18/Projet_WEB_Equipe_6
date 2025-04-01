<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/AccountController.php';
require_once __DIR__ . '/OfferController.php';
require_once __DIR__ . '/PermissionController.php';
require_once __DIR__ . '/ApplyController.php';
require_once __DIR__ . '/CompanyController.php';
require_once __DIR__ . '/abstract_controller.php';

use app\Config\ConfigDatabase;
use app\Controller\AccountController;
use app\Controller\OfferController;
use app\Controller\NotesController;
use app\Controller\PermissionController;
use app\Controller\ApplyController;
use app\Controller\CompanyController;
use PDO;

class Controller extends Abstract_Controller {
    private $accountController;
    private $offerController;
    private $notesController;
    private $permissionController;
    private $applyController;
    private $companyController;
    protected $templateEngine;
    private $pdo;

    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->pdo = (new ConfigDatabase())->getConnection();
        $this->accountController = new AccountController($this->pdo);
        $this->offerController = new OfferController($this->pdo);
        $this->notesController = new NotesController($this->pdo);
        $this->permissionController = new PermissionController($this->pdo);
        $this->applyController = new ApplyController($this->pdo);
        $this->companyController = new CompanyController($this->pdo, $this->templateEngine);
    }

    public function welcomePage() {       
        echo $this->templateEngine->render('Page_Connection.twig');
    }

    public function offerPage() {
        $companies = $this->companyController->getAllCompany();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newdata = [
                'Title_Offer' => $_POST['Title_Offer'] ?? null,
                'Contract_Offer' => $_POST['Contract_Offer'] ?? null,
                'Address_Offer' => $_POST['Address_Offer'] ?? null,
                'ActivitySector_Offer' => $_POST['ActivitySector_Offer'] ?? null,
                'Salary_Offer' => $_POST['Salary_Offer'] ?? null,
                'Description_Offer' => $_POST['Description_Offer'] ?? null,
                'Id_Company' => $_POST['Id_Company'] ?? null
            ];
            $company = $this->companyController->getCompany('Id_Company', $newdata['Id_Company']);
            $result = $this->offerController->createOffer($newdata);
            if ($result) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
        }}
        $offers = $this->offerController->getAllOffers();
        foreach ($offers as &$offer) {
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
        }
    
        // Rendu de la vue avec Twig
        return $this->templateEngine->render('Offer_Page.twig', [
            'offers' => $offers,
            'companies' => $companies,
            
        ]);
    }

    public function loginPage() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['email'] ?? '';
            setcookie("email", $username, 0, "/");
            $password = $_POST['password'] ?? '';


            $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
            if ($account && password_verify($password, $account)) {



                $_SESSION['user'] = ['firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'), 
                'Title_Offer'=> $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'), 
                'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                'Permission' => $this->permissionController->GetPermission('Id_Permissions',1, 'Description_Permission'),
                'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company')

                ];
                $Home_Page = $_SESSION['user'] ?? [];


                echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
                exit();
            } else {
                // Afficher un message d'erreur et recharger la page de connexion
                echo $this->templateEngine->render('Page_Connection.twig', [
                    'error' => 'Email ou mot de passe incorrect.'
                ]);
                exit();
            }
        }
    }

    public function homePage(){
        $Home_Page = $_SESSION['user'] ?? []; 
        echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
        exit();
    }
    
    public function Company() {
        $limit = 10; // Nombre d'entreprises par page
        $company_page = isset($_GET['company_page']) ? (int)$_GET['company_page'] : 1;
        $offset = ($company_page - 1) * $limit;
    
        $searchName = $_POST['search_name'] ?? '';
    $searchLocation = $_POST['search_location'] ?? '';

    // Si des critères de recherche sont définis
    if (!empty($searchName) || !empty($searchLocation)) {
        // Recherche d'entreprises avec les filtres de recherche
        $companies = $this->companyController->searchCompanies($searchName, $searchLocation, $limit, $offset);
        $totalCompanies = count($companies); // Total d'entreprises trouvées selon la recherche
    } else {
        // Si aucune recherche, récupérer toutes les entreprises avec pagination
        $companies = $this->companyController->getCompaniesWithPagination($limit, $offset);
        $totalCompanies = $this->companyController->getTotalCompanies();
    }

    // Calcul du nombre total de pages
    $totalPages = ceil($totalCompanies / $limit);

    // Passer les données à la vue
    echo $this->templateEngine->render('Company.twig', [
        'companies' => $companies,
        'company_page' => $company_page,
        'totalPages' => $totalPages,
        'search_name' => $searchName,
        'search_location' => $searchLocation
    ]);
    exit();
}
    public function showCompanyDetails($id) {
        $company = $this->companyController->getCompany('Id_Company', $id);
        echo $this->templateEngine->render('Company_Details.twig', ['company' => $company]);
    }
    
}
