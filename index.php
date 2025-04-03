<?php

require "vendor/autoload.php";


$loader = new \Twig\Loader\FilesystemLoader('app/View');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);


if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'home';
}



$controller = new \app\Controller\Controller($twig);




switch ($page) {
    case 'home':
        echo $controller->welcomePage();
        break;

    case 'login':
        echo $controller->loginPage();
        break;

    case 'HOME':
        echo $controller->homePage();
        break;

    case 'Account':
        echo $controller->accountPage();
       break;

    case 'CreateAccount':
        echo $controller->CreateAccount();
        break;

    case 'CreateCompany':
        echo $controller->CreateCompany();
        break;

    case 'ModifyAccount':
        echo $controller->ModifyAccount();
        break;

    case 'Search':
        echo $controller->SearchAccount();
        break; 

    case 'Search_Details':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $controller->showSearch_Details($id);
        break;

    case 'DeleteOffer':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $controller->deleteOffer($id);
        } else {
            echo 'ID non fourni ou invalide.';
        }
        break;

    case 'ModifyOffer':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            echo $controller->modifyOffer($id);
        } else {
            echo 'ID non fourni ou invalide.';
        }
        break;
    
    case 'UpdateOffer':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            echo $controller->updateOffer($id);
        } else {
            echo 'ID non fourni ou invalide.';
        }
        break;
        
    case 'Offer':
        echo $controller->offerPage(); 
        break;

    case 'DetailOffer':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $controller->showOfferDetails($id);
        } else {
        echo 'ID non fourni ou invalide.';
        }
        break;

    case 'Company':
        echo $controller->Company();
        break;

    case 'Company_Details':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $controller->showCompanyDetails($id);
        break;

    case 'Wishlist':
        echo $controller->wishlist();
        break;

    case 'LegalNotice':
        echo $controller->Legal_NoticePage();
        break;

    case 'ToggleWishlist':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $controller->toggleWishlist($id);
        } else {
            echo 'ID non fourni ou invalide.';
        }
        break;

    case 'Submit_Application':
        $id_offer = $_POST['IdOffer'] ?? null;
        echo $controller->submitApplication($id_offer);
        break;

    default:
        echo '404 Not Found';
        break;

}