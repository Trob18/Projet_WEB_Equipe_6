<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/AccountController.php';

use app\Config\ConfigDatabase;
use app\Controller\AccountController;
use PDO;

class Controller extends Abstract_Controller {
    private $accountController;
    protected $templateEngine;
    private $pdo;

    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->pdo = (new ConfigDatabase())->getConnection();
        $this->accountController = new AccountController($this->pdo);
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
                echo $this->templateEngine->render('Home_Page.twig');
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
}
