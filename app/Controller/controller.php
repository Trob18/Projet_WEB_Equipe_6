<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/AccountController.php';
require_once __DIR__ . '/OfferController.php';
require_once __DIR__ . '/PermissionController.php';
require_once __DIR__ . '/ApplyController.php';
require_once __DIR__ . '/CompanyController.php';

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
        $this->companyController = new CompanyController($this->pdo);
    }

    public function welcomePage() {       
        echo $this->templateEngine->render('Page_Connection.twig');
    }

    public function loginPage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';


            $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
            if ($account && password_verify($password, $account)) {
                $Home_Page = ['firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'), 
                'Title_Offer'=> $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'), 
                'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                'Permission' => $this->permissionController->GetPermission('Id_Permissions',1, 'Description_Permission'),
                'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company')
            
            ];

            
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
    public function Company() {
        $limit = 10; // Nombre d'entreprises par page
        $company_page = isset($_GET['company_page']) ? (int)$_GET['company_page'] : 1;
        $offset = ($company_page - 1) * $limit;
    
        $companies = $this->companyController->getCompaniesWithPagination($limit, $offset);
        $totalCompanies = $this->companyController->getTotalCompanies();
        $totalPages = ceil($totalCompanies / $limit);
    
        echo $this->templateEngine->render('Company.twig', [
            'companies' => $companies,
            'company_page' => $company_page,
            'totalPages' => $totalPages
        ]);
        exit();
    }
    
    

    public function Accueil() {
        echo $this->templateEngine->render('Accueil.twig');
    }
    
}
