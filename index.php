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
    
    default:
        echo '404 Not Found';
        break;

}





















