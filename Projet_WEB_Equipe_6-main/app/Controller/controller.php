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

class Controller extends Abstract_Controller
{
    private $accountController;
    private $offerController;
    private $notesController;
    private $permissionController;
    private $applyController;
    private $companyController;
    protected $templateEngine;
    private $pdo;

    public function __construct($templateEngine)
    {
        $this->templateEngine = $templateEngine;
        $this->pdo = (new ConfigDatabase())->getConnection();
        $this->accountController = new AccountController($this->pdo);
        $this->offerController = new OfferController($this->pdo);
        $this->notesController = new NotesController($this->pdo);
        $this->permissionController = new PermissionController($this->pdo);
        $this->applyController = new ApplyController($this->pdo);
        $this->companyController = new CompanyController($this->pdo);
    }

    public function welcomePage()
    {
        echo $this->templateEngine->render('Page_Connection.twig');

    }

    public function loginPage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            setcookie("email", $username, time() + 3600, "/");



            $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
            if ($account && password_verify($password, $account)) {
                $Home_Page = [
                    'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                    'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                    'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                    'Permission' => $this->permissionController->GetPermission('Id_Permissions', 1, 'Description_Permission'),
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


    public function AccountPage()
    {
        $username = $_COOKIE['email'] ?? '';
        $error = '';

        $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
        if ($account) {
            $idrole = $this->accountController->getAccount('Email_Account', $username, 'Id_Roles');
            $role = '';
            if ($idrole == 1) {
                $role = 'Admin';
            } elseif ($idrole == 2) {
                $role = 'Pilote';
            } elseif ($idrole == 3) {
                $role = 'Student';
            }

/*
            // baniere Note    

            $id = $this->accountController->getAccount('Email_Account', $username, 'Id_Account');



            $limit = 10; // Nombre d'entreprises par page
            $company_page = isset($_GET['company_page']) ? (int) $_GET['company_page'] : 1;
            $offset = ($company_page - 1) * $limit;

            $companies = $this->companyController->getCompaniesWithPagination($limit, $offset);
            $totalCompanies = $this->companyController->getTotalCompanies();
            $totalPages = ceil($totalCompanies / $limit);

            $company = 'DataConsult';

            $id_company = $this->companyController->getCompany('Name_Company', $company, 'Id_Company');
            $liste_note = $this->notesController->getAllNotesArg("{$id_company} = Id_Company");
            $note_global = 0;

            foreach ($liste_note as $note) {
                $note_global += $note["Note"];
            }

            $check = 0;
            if (!empty($_POST)) {
                if (isset($_POST['rating'])) { //;
                    foreach ($liste_note as $note) {
                        if ($note["Id_Account"] == $id) {
                            $error = 'Vous avez déjà notez cette entreprise';
                            $check = 1;
                        }
                    }
                    if ($check == 0) {
                        $rating = [$_POST['rating'] ?? '', $id, $id_company];
                        $this->notesController->createNote($rating);
                        $check = 0;
                    }


                }
            }
            */


            // baniere 


            $Home_Page = [
                'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                'emailaccount' => $this->accountController->getAccount('Email_Account', $username, 'Email_Account'),
                'imageaccount' => $this->accountController->getAccount('Email_Account', $username, 'Image_Account'),
                'descriptionaccount' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                'numberaccount' => $this->accountController->getAccount('Email_Account', $username, 'PhoneNumber_Account'),
                'idrole' => $role/*,
                'companies' => $companies,
                'company_page' => $company_page,
                'totalPages' => $totalPages,
                'noteglobal' => $note_global / count($liste_note),
                'error' => $error*/

            ];




            echo $this->templateEngine->render('Account.twig', $Home_Page);
            exit();
        } else {

            // Afficher un message d'erreur et recharger la page de connexion
            echo $this->templateEngine->render('Page_Connection.twig', [
                'error' => 'Email ou mot de passe incorrect.'
            ]);
            exit();
        }






    }

    /*$error = '';
            echo $this->templateEngine->render('Modifyaccount.twig', ['error' => $error]);*/

    public function ModifyAccountPage()
    {
        $username = $_COOKIE['email'] ?? '';
        $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');


        if ($account) {

            $error = '';

            $id = $this->accountController->getAccount('Email_Account', $username, 'Id_Account');
            if (!empty($_POST)) {
                if (isset($_POST['update_account'])) {

                    $username = $_POST['email'] ?? ($_COOKIE['email'] ?? '');


                    $error = '';
                    $password = $_POST['password'] ?? '';
                    $password2 = $_POST['confirm-password'] ?? '';
                    if ($password != $password2) {
                        $error = 'Different mot de passe mis';
                        $_POST = [];
                        echo $this->templateEngine->render('Modifyaccount.twig', ['error' => $error]);
                        exit();
                    } else {
                        $newData = [
                            'Description_Account' => $_POST['details'] ?? '',
                            'Studies_Account' => $_POST['school'] ?? '',
                            'Address_Account' => $_POST['address'] ?? '',
                            'PhoneNumber_Account' => $_POST['phone'] ?? '',
                            'Password_Account' => $password
                        ];

                        $result = $this->accountController->editAccount($id, $newData);
                        $_POST = [];


                        header("Location: ?page=Account");
                        exit();
                    }
                } elseif (isset($_POST['delete_account'])) {
                    $this->accountController->removeAccount($id);
                    setcookie("email", "", time() - 3600, "/");

                    echo $this->templateEngine->render('Page_Connection.twig', [
                        'error' => 'Compte suprimer avec succès.'
                    ]);
                    exit();
                }

            }
            echo $this->templateEngine->render('Modifyaccount.twig', ['error' => $error]);
            exit();

        } else {
            // Afficher un message d'erreur et recharger la page de connexion
            echo $this->templateEngine->render('Page_Connection.twig', [
                'error' => 'Email ou mot de passe incorrect.'
            ]);
            exit();
        }

    }

    public function Legal_NoticePage()
    {
        echo $this->templateEngine->render('Legal_Notice.twig', ['error' => ""]);
        exit();
    }





}
