<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/AccountController.php';
require_once __DIR__ . '/OfferController.php';
require_once __DIR__ . '/PermissionController.php';
require_once __DIR__ . '/ApplyController.php';
require_once __DIR__ . '/CompanyController.php';
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

class Controller extends Abstract_Controller {
    private $accountController;
    private $offerController;
    private $notesController;
    private $permissionController;
    private $applyController;
    private $companyController;
    private $wishlistController;
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
        $this->wishlistController = new WishlistController($this->pdo);
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
                
                $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], 'Id_Account');
                $allCandidatures = $this->applyController->CountgetAllApply($id_account);

                $_SESSION['user'] = ['lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                    'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                    'Description' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                    'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                    'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                    'Permission' => $this->permissionController->GetPermission('Id_Permissions', $id_account, 'Description_Permission'),
                    'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                    'Candidatures' => count($allCandidatures),
                    'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company'),
                    'Wishlist' => is_array($this->wishlistController->getWishlist('Id_Account', $id_account)) ? count($this->wishlistController->getWishlist('Id_Account', $id_account)) : 0,
                    'id'=> $this->accountController->getAccount('Email_Account', $username, 'Id_Account'),
                    'user_role' => $this->accountController->getAccount('Email_Account', $username, 'Id_Roles')
                ];
                $Home_Page = $_SESSION['user'] ?? [];

                
                echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
                exit();            
            } else {
                echo $this->templateEngine->render('Page_Connection.twig', [
                    'error' => 'Email ou mot de passe incorrect.'
                ]);
                exit();
            }
        }
    }
    public function homePage() {
        session_start();
        if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
            $username = $_COOKIE['email'] ?? null;
            $id_account = $_SESSION['user']['id'];
            

            if ($username) {
                $allCandidatures = $this->applyController->CountgetAllApply($id_account);
                $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], 'Id_Account');
                $_SESSION['user'] = [
                    'Email_Account' => $username, 
                    'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                    'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                    'Description' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                    'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                    'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                    'Permission' => $this->permissionController->GetPermission('Id_Permissions', $id_account, 'Description_Permission'),
                    'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                    'Candidatures' => count($allCandidatures),
                    'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company'),
                    'Wishlist' => is_array($this->wishlistController->getWishlist('Id_Account', $id_account)) ? count($this->wishlistController->getWishlist('Id_Account', $id_account)) : 0,
                    'id' => $id_account,
                    'user_role' => $this->accountController->getAccount('Email_Account', $username, 'Id_Roles')
                ];
            }
        }
        
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

        if (isset($_FILES['image']) && $_SERVER['REQUEST_METHOD'] === 'POST') {     
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

                    $uploadDir = "C:/CESI/Code/Livrable/assets/img/"; 
                    $fileName = basename($_FILES['image']['name']);
                    $targetFilePath = $uploadDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                    if (in_array($fileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                            $this->accountController->uploadimg($email, $fileName);
                            echo $this->templateEngine->render('CreateAccount.twig', [
                                'user_role' => $user_role,
                                'Success' => 'Compte créé avec succès !'
                            ]);
                            exit();
                        } else {
                            echo "Erreur lors du téléversement.";
                        }
                    } else {
                        echo "Format d'image non valide. Formats autorisés : jpg, jpeg, png, gif, webp.";
                    }          
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



    public function CreateCompany(){
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

        if (isset($_FILES['image']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['choix'] ?? null;
            $id = ($id === 'Etudiant') ? 3 : (($id === 'Pilote') ? 2 : 0);

            $nom = $_POST['nom'] ?? null;
            $email = $_POST['email'] ?? null;
            $address = $_POST['addressCompany'] ?? null;
            $description = $_POST['descriptionCompany'] ?? null;


            $result = $this->companyController->createCompany($nom, $email, $address, $description);
            if ($result == TRUE) {

                $uploadDir = "C:/CESI/Code/Livrable/assets/img/"; 
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $this->companyController->uploadimg($email, $fileName);
                        echo $this->templateEngine->render('CreateCompany.twig', [
                            'user_role' => $user_role,
                            'Success' => 'Compte créé avec succès !'
                        ]);
                        exit();
                    } else {
                        echo "Erreur lors du téléversement.";
                    }
                } else {
                    echo "Format d'image non valide. Formats autorisés : jpg, jpeg, png, gif, webp.";
                }          

                    
                
                exit();
            } else {
                echo $this->templateEngine->render('CreateCompany.twig', [
                    'Error' => 'Erreur lors de la création du compte.'
                ]);
                exit();
            }
            }  
        

        
        
        $CreaPage=['user_role' => $user_role];
        if ($type_Account == 1){
            $CreaPage = array_merge($CreaPage, ['Admin' => 1]);
        }
        echo $this->templateEngine->render('CreateCompany.twig', $CreaPage);

    }


    public function Company() {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? [];
        $user_role = $_SESSION['user']['user_role'];

        $limit = 10;
        $company_page = isset($_GET['company_page']) ? (int)$_GET['company_page'] : 1;
        $offset = ($company_page - 1) * $limit;
    
        $searchName = $_POST['search_name'] ?? '';
        $searchLocation = $_POST['search_location'] ?? '';

        if (!empty($searchName) || !empty($searchLocation)) {
            $companies = $this->companyController->searchCompanies($searchName, $searchLocation, $limit, $offset);
            $totalCompanies = count($companies); 
        } else {
            $companies = $this->companyController->getCompaniesWithPagination($limit, $offset);
            $totalCompanies = $this->companyController->getTotalCompanies();
        }
        $totalPages = ceil($totalCompanies / $limit);
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
        $username = $_COOKIE['email'] ?? '';
    
        $error = '';
        if(isset($_POST['rating'])) {
            $id_company = $id;
            $id_account = $this->accountController->getAccount('Email_Account', $username, 'Id_Account');
            
            $liste_note = $this->notesController->getAllNotesArg("{$id} = Id_Company");
            if (!is_array($liste_note)) {
                $liste_note = [];
            }
            $check = 0;
            if (!empty($_POST)) {
                if (isset($_POST['rating'])) {
                    foreach ($liste_note as $note) {
                        if ($note["Id_Account"] == $id_account) {
                            $error = 'Vous avez déjà notez cette entreprise';
                            $check = 1;
                        }
                    }
                    if ($check == 0) {
                        $rating = ['Note' => $_POST['rating'] ?? '', 'Id_Account' => $id_account, 'Id_Company' => $id_company];
                        $this->notesController->createNote($rating);
                        $check = 0;
                    }
                }
            }
        }
        
        $liste_note = $this->notesController->getAllNotesArg("{$id} = Id_Company");
        $note_global = 0;
        $note_message = '';
        $note_moyenne = 0;
        
        if (empty($liste_note) || $liste_note === "Aucune Note Trouvée !") {
            $note_message = "Soyez le premier à noter cette entreprise";
        } else {
            foreach ($liste_note as $note) {
                $note_global += $note["Note"];
            }
            $note_moyenne = count($liste_note) > 0 ? round($note_global / count($liste_note), 1) : 0;
        }
        
        if (isset($_POST['delete_company'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            $this->companyController->removeCompany($id);
            if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
                $username = $_COOKIE['email'] ?? null;
                $id_account = $_SESSION['user']['id'];
                
    
                if ($username) {
                    $allCandidatures = $this->applyController->CountgetAllApply($id_account);
                    $_SESSION['user'] = [
                        'Email_Account' => $username, 
                        'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                        'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                        'Description' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                        'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                        'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                        'Permission' => $this->permissionController->GetPermission('Id_Permissions', $id_account, 'Description_Permission'),
                        'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                        'Candidatures' => count($allCandidatures),
                        'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company'),
                        'Wishlist' => is_array($this->wishlistController->getWishlist('Id_Account', $id_account)) ? count($this->wishlistController->getWishlist('Id_Account', $id_account)) : 0,
                        'id' => $id_account,
                        'user_role' => $this->accountController->getAccount('Email_Account', $username, 'Id_Roles')
                    ];
                }
            }
            
            $Home_Page = $_SESSION['user'] ?? [];
            echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
            exit();
        }
        elseif (isset($_POST['update_company'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            $error = '';
            $newData = [
                'Description_Company' => $_POST['details'] ?? '',
                'Address_Company' => $_POST['address'] ?? '',
                'user_role' => $user_role
            ];
            $result = $this->companyController->editCompany($id, $newData);
            echo $this->templateEngine->render('Company_Details.twig', [
                'company' => $this->companyController->getCompany('Id_Company', $id),
                'user_role' => $user_role,
                'success' => 'Compte mis à jour avec succès',
                'noteglobal' => $note_moyenne,
                'note_message' => $note_message,
                'error' => $error
            ]);
            exit();
        }
        elseif (isset($_POST['modify_company'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            echo $this->templateEngine->render('Modifycompany_Page.twig', ['user_role' => $user_role]);
            exit();
        }
        else {
            $company = $this->companyController->getCompany('Id_Company', $id);
            echo $this->templateEngine->render('Company_Details.twig', [
                'company' => $company, 
                'noteglobal' => $note_moyenne,
                'note_message' => $note_message,                
                'user_role' => $user_role,
                'error' => $error
            ]);  
        }
    }






    public function SearchAccount(){
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? []; 
        $user_role = $_SESSION['user']['user_role'];
        if ($user_role==3) {
            exit("Erreur : L'utilisateur n'a pas les permissions requises.");
        }
        $limit = 14;
        $account_page = isset($_GET['account_page']) ? (int)$_GET['account_page'] : 1;
        $offset = ($account_page - 1) * $limit;

        $searchName = $_POST['search_name'] ?? '';

        if (!empty($searchName)) {
            $accounts = $this->accountController->searchAccounts($searchName, $limit, $offset);
            $totalAccounts = count($accounts); 
        } else {
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
        if (isset($_POST['delete_account'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            $this->accountController->removeAccount($id);
            $username = $_COOKIE['email'] ?? null;
                $id_account = $_SESSION['user']['id'];
                
    
                if ($username) {
                    $allCandidatures = $this->applyController->CountgetAllApply($id_account);
                    $_SESSION['user'] = [
                        'Email_Account' => $username, 
                        'lastname' => $this->accountController->getAccount('Email_Account', $username, 'LastName_Account'),
                        'firstname' => $this->accountController->getAccount('Email_Account', $username, 'FirstName_Account'),
                        'Description' => $this->accountController->getAccount('Email_Account', $username, 'Description_Account'),
                        'Title_Offer' => $this->offerController->getOffer('Id_Offer', 1, 'Title_Offer'),
                        'Notes' => $this->notesController->getNote('Id_Notes', 1, 'Note'),
                        'Permission' => $this->permissionController->GetPermission('Id_Permissions', 1, 'Description_Permission'),
                        'Date' => $this->applyController->getApply('Id_Application', 1, 'Date_Application'),
                        'Candidatures' => count($allCandidatures),
                        'Company' => $this->companyController->getCompany('Id_Company', 1, 'Name_Company'),
                        'Wishlist' => is_array($this->wishlistController->getWishlist('Id_Account', $id_account)) ? count($this->wishlistController->getWishlist('Id_Account', $id_account)) : 0,
                        'id' => $id_account,
                        'user_role' => $this->accountController->getAccount('Email_Account', $username, 'Id_Roles')
                    ];
                }
            
            
            $Home_Page = $_SESSION['user'] ?? [];
            echo $this->templateEngine->render('Home_Page.twig', $Home_Page);
            exit();
        }
        elseif (isset($_POST['update_account'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            $error = '';
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['confirm-password'] ?? '';
            
            if ($password != $password2) {
                $error = 'Différents mots de passe saisis';
                echo $this->templateEngine->render('Modifyaccount_Page.twig', [
                    'error' => $error,
                    'user_role' => $user_role
                ]);
                exit();
            } else {
                $newData = [
                    'Description_Account' => $_POST['details'] ?? '',
                    'Studies_Account' => $_POST['school'] ?? '',
                    'Address_Account' => $_POST['address'] ?? '',
                    'PhoneNumber_Account' => $_POST['phone'] ?? '',
                    'user_role' => $user_role
                ];
                if (!empty($password)) {
                    $newData['Password_Account'] = $password;
                }
                $result = $this->accountController->editAccount($id, $newData);
                echo $this->templateEngine->render('SearchAccount_Details_Page.twig', [
                    'account' => $this->accountController->getAccount('Id_Account', $id),
                    'user_role' => $user_role,
                    'success' => 'Compte mis à jour avec succès'
                ]);
                exit();
            }
        }

        elseif (isset($_POST['modify_account'])) {
            if ($user_role==3) {
                exit("Erreur : L'utilisateur n'a pas les permissions requises.");
            }
            echo $this->templateEngine->render('Modifyaccount_Page.twig', ['user_role' => $user_role]);
            exit();
        }
        else {
            $account = $this->accountController->getAccount('Id_Account', $id);
            echo $this->templateEngine->render('SearchAccount_Details_Page.twig', [
                'account' => $account, 
                'user_role' => $user_role
            ]);    
        }
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
                'Id_Company' => $_POST['Id_Company'] ?? null,
                'user_role' => $user_role
            ];
            $company = $this->companyController->getCompany('Id_Company', $newdata['Id_Company']);
            $result = $this->offerController->createOffer($newdata);
            if ($result) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
        }}
        $offers = $this->offerController->getAllOffers();
        $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], "Id_Account");
        $wishlistIds = $this->wishlistController->getUserWishlist($id_account);

        foreach ($offers as &$offer) {
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
            $offer['inWishlist'] = in_array($offer['Id_Offer'], $wishlistIds);
        }

        return $this->templateEngine->render('Offer_Page.twig', [
            'offers' => $offers,
            'companies' => $companies,
            'user_role' => $user_role

            
        ]);
    }

    public function showOfferDetails($id) {
        session_start();
        $Home_Page_header = $_SESSION['user'] ?? []; 
        $user_role = $_SESSION['user']['user_role'];
        $offer = $this->offerController->getOffer('Id_Offer', $id);
        if (is_array($offer)) {
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
        }
        echo $this->templateEngine->render('Voir_plus_page.twig', [
            'id_offer' => $id,
            'offer' => $offer, 
            'user_role' => $user_role
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

            $company = $this->companyController->getCompany('Name_Company', $company_name);

            if (is_string($company) && $company === "Company introuvable!") {
                echo "Erreur : Entreprise non trouvée.";
                return;
            }
            
            $company_id = $company['Id_Company'] ?? null;
            if (!$company_id) {
                echo "Erreur : ID d'entreprise non trouvé.";
                return;
            }
            $offerData = [
                'Title_Offer' => $title,
                'Id_Company' => $company_id,
                'Address_Offer' => $address,
                'Contract_Offer' => $contract,
                'ActivitySector_Offer' => $sector,
                'Salary_Offer' => $salary,
                'Description_Offer' => $description,
                'user_role' => $user_role
            ];
            
            $result = $this->offerController->editOffer($id, $offerData);
            header('Location: index.php?page=Offer');
        }
    }
        
    public function wishlist() {
    session_start();
    $user_role = $_SESSION['user']['user_role'] ?? null;

    $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], "Id_Account");


    if (is_string($id_account) && !is_numeric($id_account)) {
        echo $this->templateEngine->render('Wishlist_Page.twig', [
            'user_role' => $user_role,
            'error' => 'Erreur lors de la récupération du compte utilisateur'
        ]);
        return;
    }

    $wishlistIds = $this->wishlistController->getUserWishlist($id_account);

    if (!is_array($wishlistIds)) {
        echo $this->templateEngine->render('Wishlist_Page.twig', [
            'user_role' => $user_role,
            'error' => 'Erreur lors de la récupération de la wishlist'
        ]);
        return;
    }

    if (empty($wishlistIds)) {
        echo $this->templateEngine->render('Wishlist_Page.twig', [
            'user_role' => $user_role
        ]);
        return;
    }

    $wishlistOffers = [];
    foreach ($wishlistIds as $offerId) {
        $offer = $this->offerController->getOffer('Id_Offer', $offerId);
        if ($offer && is_array($offer)) {  
        
            $company = $this->companyController->getCompany('Id_Company', $offer['Id_Company']);
            if (is_array($company)) {  
                $offer['Company_Name'] = $company['Name_Company'] ?? 'Non spécifié';
                $wishlistOffers[] = $offer;
            }
        }
    }

    echo $this->templateEngine->render('Wishlist_Page.twig', [
        'wishlistOffers' => $wishlistOffers,
        'user_role' => $user_role
    ]);
    }

    public function toggleWishlist($id) {
        session_start();
        $id_account = $this->accountController->getAccount('Email_Account', $_COOKIE['email'], "Id_Account");
        if ($this->wishlistController->isInWishlist($id_account, $id)) {
            $this->wishlistController->removeFromWishlist($id_account, $id);
        } else {
            $this->wishlistController->addToWishlist($id_account, $id);
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=Offer';
        header("Location: $referer");
        exit;
    }




    public function Legal_NoticePage()
    {
        session_start();
        $user_role = $_SESSION['user']['user_role'] ?? null;
        echo $this->templateEngine->render('Legal_Notice.twig', ['error' => "", 'user_role' => $user_role]);
        exit();
    }










    public function submitApplication($id_offer)
    {
        session_start();
        $id = $_SESSION['user']['id'];
        if (!$id) {
            echo "Erreur : L'ID de l'utilisateur est manquant.";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coverLetter = !empty($_POST['cover_letter']) ? $_POST['cover_letter'] : null;
            $cvUploaded = isset($_FILES['cv']) && $_FILES['cv']['error'] === 0;
            if (empty($coverLetter) && !$cvUploaded) {
                echo "Erreur : Vous devez envoyer au moins une lettre de motivation ou un CV.";
                exit();
            }
            $IdOffer = $id_offer;

        
            if (!$IdOffer) {
                echo "ID de l'offre non trouvé";
            }
            if (!$id) {
                echo "ID de l'utilisateur non trouvé";
            }

            if ($cvUploaded) {
                $cvTmpName = $_FILES['cv']['tmp_name'];
                $cvName = $_FILES['cv']['name'];
                $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));
                $cvNameUnique = uniqid('cv_', true) . '.' . $cvExt;
                $cvDirectory = 'assets/cv/';
                if (!is_dir($cvDirectory) && !mkdir($cvDirectory, 0777, true)) {
                    echo "Erreur lors de la création du dossier de stockage.";
                    exit();
                }
                $cvPath = $cvDirectory . $cvNameUnique; 
                if (!move_uploaded_file($cvTmpName, $cvPath)) {
                    echo "Erreur lors du téléchargement du fichier.";
                    exit();
                }
            } else {
                $cvNameUnique = null; 
            }
            $this->applyController->storeApplication($id, $IdOffer, $coverLetter, $cvNameUnique);

            echo "Candidature enregistrée avec succès !";
            exit();
        } else {
            echo "Méthode invalide.";
            exit();
        }
    }
}