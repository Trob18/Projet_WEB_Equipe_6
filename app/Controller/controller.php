<?php

namespace app\Controller;

// use app\Model\TaskModel;


class Controller extends Abstract_Controller {

    public function __construct($templateEngine) {
        // $this->model = new TaskModel();
        $this->templateEngine = $templateEngine;
    }


    public function welcomePage() {
        echo $this->templateEngine->render('Page_Connection.twig.html');
    }


    public function loginPage(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
    
            // Vérification simple (à remplacer par une base de données)
            if ($username == 'admin' && $password == 'password') {
                echo $this->templateEngine->render('Home_Page.twig.html');
                echo "<script>console.log('Page Réussis');</script>";
            } else {
                return $this->templateEngine->render('Page_Connection.twig.html', [
                    'error' => 'Nom d’utilisateur ou mot de passe incorrect.'
                ]);
            }
        }
    }





















}































