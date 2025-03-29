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
        echo $this->templateEngine->render('Page_Connection.twig.html');
    }

    public function loginPage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountController->getAccount('Email_Account', $email, 'Password_Account');
            echo $account;
            if ($account == $password) {
                echo $this->templateEngine->render('Home_Page.twig.html');
                exit();            
            } else {
                echo $this->templateEngine->render('Page_Connection.twig.html', [
                    'error' => 'Nom d’utilisateur ou mot de passe incorrect.'
                ]);
            }
        } else {
            echo $this->templateEngine->render('Page_Connection.twig.html');
        }
    }
}
