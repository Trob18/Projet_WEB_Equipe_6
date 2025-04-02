<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/AccountController.php';
require_once __DIR__ . '/OfferController.php';
require_once __DIR__ . '/PermissionController.php';
require_once __DIR__ . '/ApplyController.php';
require_once __DIR__ . '/CompanyController.php';
require_once __DIR__ . '/abstract_controller.php';
require_once __DIR__ . '/WishlistController.php';

use app\Config\ConfigDatabase;
use app\Controller\AccountController;
use app\Controller\OfferController;
use app\Controller\NotesController;
use app\Controller\PermissionController;
use app\Controller\ApplyController;
use app\Controller\CompanyController;
use app\Controller\WishlistController;
use PDO;

class Controller extends Abstract_Controller
{
    private $accountController;
    private $offerController;
    private $notesController;
    private $permissionController;
    private $applyController;
    private $companyController;
    private $wishlistController;
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
        $this->companyController = new CompanyController($this->pdo, $this->templateEngine);
        $this->wishlistController = new WishlistController($this->pdo);
    }

    public function welcomePage()
    {
        echo $this->templateEngine->render('Page_Connection.twig');
    }

    public function loginPage()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['email'] ?? '';
            setcookie("email", $username, 0, "/");
            $password = $_POST['password'] ?? '';


            $account = $this->accountController->getAccount('Email_Account', $username, 'Password_Account');
            if ($account && password_verify($password, $account)) {

                $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], 'Id_Account');
                $allCandidatures = $this->applyController->CountgetAllApply($id_account);


                $_SESSION['user'] = [
                    'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                    'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                    'Description' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                    'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                    'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                    'Permission' => $this->permissionController->GetPermission('Id_Permissions', 1, 'Description_Permission'),
                    'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                    'Candidatures' => count($allCandidatures),
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

    public function homePage()
    {
        session_start();
        $Home_Page = $_SESSION['user'] ?? [];
        echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
        exit();
    }

    public function Company()
    {
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
    public function showCompanyDetails($id)
    {
        $company = $this->companyController->getCompany('Id_Company', $id);
        echo $this->templateEngine->render('Company_Details.twig', ['company' => $company]);
    }

    public function Wishlist()
    {
        session_start();
        $Home_Page = $_SESSION['user'] ?? [];
        $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], 'Id_Account');
        echo $this->templateEngine->render('Wishlist.twig');
    }

    public function offerPage() {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? []; 
        $user_role = $_SESSION['user']['user_role'];

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
            'user_role' => $user_role

            
        ]);
    }


    public function showOfferDetails($id) {
        session_start();
    
        // Récupération des détails de l'offre
        $offer = $this->offerController->getOffer('Id_Offer', $id);
        if (is_array($offer)) {
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
        }
    
        // Initialisation des messages
        $success_message = $error_message = "";
    
        // Traitement du formulaire si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
            // Récupération des données du formulaire
            $idAccount = $_POST['IdAccount'] ?? null;
            $idOffer = $_POST['IdOffer'] ?? null;
            $coverLetter = $_POST['Lettre_Motivation'] ?? null;
            $cvFile = $_FILES['cv'] ?? null;
    
            // Vérification des données obligatoires
            if (!$idAccount || !$idOffer) {
                $error_message = "Erreur : informations manquantes.";
            } else {
                // Appel de la méthode pour stocker la candidature
                $applyController = new ApplyController($this->pdo);
                $result = $applyController->storeApply($idAccount, $cvFile, $coverLetter, $idOffer);
    
                if ($result === true) {
                    $success_message = "Candidature envoyée avec succès.";
                } else {
                    $error_message = $result; // Message d'erreur retourné par la méthode
                }
            }
        }
    
        // Affichage de la page avec Twig
        echo $this->templateEngine->render('Voir_plus_page.twig', [
            'offer' => $offer, 
            'success_message' => $success_message,
            'error_message' => $error_message
        ]);
    }
    

    public function deleteOffer($id) {
        $result = $this->offerController->removeOffer($id);
        header('Location: index.php?page=Offer');
        
        ;
    }
    public function modifyOffer($id) {

        $offer = $this->offerController->getOffer('Id_Offer', $id);
        if (is_array($offer)) {
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
        }
        return $this->templateEngine->render('Modif_Offer.twig', [
            'offer' => $offer,
            ]);
    }
    
    public function updateOffer($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $company_name = $_POST['company'] ?? '';
            $address = $_POST['address'] ?? '';
            $contract = $_POST['contract'] ?? '';
            $sector = $_POST['sector'] ?? '';
            $salary = $_POST['salary'] ?? '';
            $description = $_POST['description'] ?? '';
    
            // Rechercher l'entreprise par son nom en utilisant la méthode getCompany existante
            $company = $this->companyController->getCompany('Name_Company', $company_name);
            
            // Vérifier si l'entreprise existe et n'est pas une chaîne d'erreur
            if (is_string($company) && $company === "Company introuvable!") {
                // Gérer l'erreur - entreprise non trouvée
                echo "Erreur : Entreprise non trouvée.";
                return;
            }
            
            $company_id = $company['Id_Company'] ?? null;
            if (!$company_id) {
                // Gérer l'erreur - ID d'entreprise non trouvé
                echo "Erreur : ID d'entreprise non trouvé.";
                return;
            }
            
            // Créer un tableau avec les données à mettre à jour
            $offerData = [
                'Title_Offer' => $title,
                'Id_Company' => $company_id,
                'Address_Offer' => $address,
                'Contract_Offer' => $contract,
                'ActivitySector_Offer' => $sector,
                'Salary_Offer' => $salary,
                'Description_Offer' => $description
            ];
            
            $result = $this->offerController->editOffer($id, $offerData);
            header('Location: index.php?page=Offer');
        }
    }

    public function submitApplication()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coverLetter = !empty($_POST['cover_letter']) ? $_POST['cover_letter'] : null;
            
            // Vérification de l'upload du CV
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
                $cvPath = 'assets/cv/' . basename($_FILES['cv']['name']);
                move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
            } else {
                $cvPath = null;
            }

            // Sauvegarder la candidature
            $this->applyController->storeApplication($coverLetter, $cvPath);

            echo "Candidature enregistrée avec succès !";
            exit();
        } else {
            echo "Méthode invalide.";
            exit();
        }
    }
}
