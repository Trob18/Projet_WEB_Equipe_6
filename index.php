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





$controller = new \app\Controller\Controller($twig);




switch ($page) {
    case 'home':
        echo $controller->welcomePage();
        break;
    case 'login':
        echo $controller->loginPage();
        break;
    case 'Accueil':
        echo $controller->homePage();
        break;
    case 'Offer':
        echo $controller->offerPage(); 
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
    // case 'Wishlist':
    //     echo $controller->//fonction dans controller.php
    //     break;
    // case 'Settings':
    //     echo $controller->//fonction dans controller.php
    //     break;
    // case 'Account':
    //     echo $controller->//fonction dans controller.php
    default:
        echo '404 Not Found';
        break;
}
