<?php

require "vendor/autoload.php";
//Charge Composer et autoload toutes les classes du projet


$loader = new \Twig\Loader\FilesystemLoader('app/View');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);
//Initialisation de twig et définir le répertoire View pour le moteur de template


if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'home';
}
//Définit l'url ou on se trouve


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





    // case 'Offer':
    //     echo $controller->//fonction dans controller.php
    //     break;
    case 'Company':
        echo $controller->Company(); //fonction dans controller.php
        break;
    case 'Company_Details':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $controller->showCompanyDetails($id); //fonction dans controller.php
        break;
    // case 'Wishlist':
    //     echo $controller->//fonction dans controller.php
    //     break;
    default:
        echo '404 Not Found';
        break;

}





















