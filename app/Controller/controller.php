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
                'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company'),
                'user_role' => $this->accountController->getAccount('Email_Account', $username, 'Id_Roles')
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
        session_start();
        $Home_Page = $_SESSION['user'] ?? []; 
        echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
        exit();
    }

    public function accountPage(){
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? []; 
        $username = $_COOKIE['email'] ?? '';


        $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
        if ($account) {
            $idrole = $this->accountController->getAccount('Email_Account', $username, 'Id_Roles');
            if ($idrole == 1) {
                $role = 'Admin';
            } elseif ($idrole == 2) {
                $role = 'Pilote';
            } elseif ($idrole == 3) {
                $role = 'Student';
            }


            $Home_Page = [
                'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                'emailaccount' => $this->accountController->getAccount('Email_Account', $username, 'Email_Account'),
                'imageaccount' => $this->accountController->getAccount('Email_Account', $username, 'Image_Account'),
                'descriptionaccount' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                'numberaccount' => $this->accountController->getAccount('Email_Account', $username, 'PhoneNumber_Account'),
                'idrole' => $role

            
            ];

            $home_Page= array_merge($Home_Page, $Home_Page_header);


            echo $this->templateEngine->render('Account_Page.twig', $home_Page);
            exit();
        } else {
            
            // Afficher un message d'erreur et recharger la page de connexion
            echo $this->templateEngine->render('Page_Connection.twig', [
                'error' => 'Email ou mot de passe incorrect.'
            ]);
            exit();
        }
    }

    public function CreateAccount(){
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? [];
        $user_role = $_SESSION['user']['user_role'];

        if (!isset($_COOKIE['email'])) {
            exit("Erreur : Aucun email trouvé dans les cookies.");
        }

        $type_Account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], 'Id_Roles');


        if (!in_array($type_Account, [1, 2])) {
            exit("Erreur : L'utilisateur n'a pas les permissions requises.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['choix'] ?? null;
            $id = ($id === 'Etudiant') ? 3 : (($id === 'Pilote') ? 2 : 0);

            $nom = $_POST['nom'] ?? null;
            $prenom = $_POST['prenom'] ?? null;
            $dateNaissance = $_POST['dateNaissance'] ?? null;
            $email = $_POST['email'] ?? null;
            $telephone = $_POST['telephone'] ?? null;
            $password = $_POST['password1'] ?? null;
            $confirmPassword = $_POST['confirm-password'] ?? null;


            if ($password !== $confirmPassword) {
                echo "Erreur : les mots de passe ne correspondent pas.";
                return;
            } else {
                $result = $this->accountController->createAccount($nom, $prenom, $dateNaissance, $email, $telephone, $password, $id);
                if ($result == TRUE) {
                    echo $this->templateEngine->render('CreateAccount.twig', [
                        'Success' => 'Compte créé avec succès !'
                    ]);
                    exit();
                } else {
                    echo $this->templateEngine->render('CreateAccount.twig', [
                        'Error' => 'Erreur lors de la création du compte.'
                    ]);
                }
            }  
        }

        
        
        $CreaPage=['user_role' => $user_role];
        if ($type_Account == 1){
            $CreaPage = array_merge($CreaPage, ['Admin' => 1]);
        }
        echo $this->templateEngine->render('CreateAccount.twig', $CreaPage);

    }








    public function ModifyAccount(){
        echo $this->templateEngine->render('Modifyaccount_Page.twig');
    }












    public function Company() {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? [];
        $user_role = $_SESSION['user']['user_role'];

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
            'search_location' => $searchLocation,
            'user_role' => $user_role
        ]);
        exit();
    }





    public function showCompanyDetails($id) {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? [];
        $user_role = $_SESSION['user']['user_role'];

        $company = $this->companyController->getCompany('Id_Company', $id);
        echo $this->templateEngine->render('Company_Details.twig', [
            'company' => $company, 
            'user_role' => $user_role
        ]);
    }






    public function SearchAccount(){
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? []; 
        $user_role = $_SESSION['user']['user_role'];
        if ($user_role==3) {
            exit("Erreur : L'utilisateur n'a pas les permissions requises.");
        }
        $limit = 10;
        $account_page = isset($_GET['account_page']) ? (int)$_GET['account_page'] : 1;
        $offset = ($account_page - 1) * $limit;

        $searchName = $_POST['search_name'] ?? '';


        // Si des critères de recherche sont définis
        if (!empty($searchName)) {
            // Recherche d'entreprises avec les filtres de recherche
            $accounts = $this->accountController->searchAccounts($searchName, $limit, $offset);
            $totalAccounts = count($accounts); // Total d'entreprises trouvées selon la recherche
        } else {
            // Si aucune recherche, récupérer toutes les entreprises avec pagination
            $accounts = $this->accountController->getAccountWithPagination($limit, $offset);
            $totalAccounts = $this->accountController->getTotalAccount();
        }





        $totalPages = ceil($totalAccounts / $limit);

        echo $this->templateEngine->render('SearchAccount_Page.twig', [
            'accounts' => $accounts,
            'account_page' => $account_page,
            'totalPages' => $totalPages,
            'user_role' => $user_role
        ]);
        exit();


    }
    public function showSearch_Details($id) {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? [];
        $user_role = $_SESSION['user']['user_role'];

        $account = $this->accountController->getAccount('Id_Account', $id);
        echo $this->templateEngine->render('SearchAccount_Details_Page.twig', ['account' => $account, 'user_role' => $user_role]);
    }



}