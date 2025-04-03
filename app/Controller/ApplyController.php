<?php

namespace app\Controller;

use app\Model\ApplyModel;
use PDO;

require_once __DIR__ . '/../Model/ApplyModel.php';



class ApplyController
{
    private $ApplyModel;

    public function __construct($pdo)
    {
        $this->ApplyModel = new ApplyModel($pdo);
    }

    public function getApply($column, $value, $selectColumn = '*')
    {
        $account = $this->ApplyModel->getApply($column, $value, $selectColumn);
        return $account ? $account : "Apply introuvable!";
    }

    public function getAllApply()
    {
        $accounts = $this->ApplyModel->getAllApply();
        return $accounts ? $accounts : "Aucun Apply trouvé.";
    }

    public function CountgetAllApply($id)
    {
        $accounts = $this->ApplyModel->CountgetAllApply($id);
        return $accounts;
    }

    public function removeApply($IdApply)
    {
        $account = $this->ApplyModel->getApplyById($IdApply);
        if (!$account) {
            return false; 
        }

        $result = $this->ApplyModel->removeApply($IdApply);
        return $result ? true : false; 
    }

    public function removeAllApply($IdApply)
    {
        $account = $this->ApplyModel->getAllApply();
        if (!$account) {
            return false; 
        }

        $result = $this->ApplyModel->removeAllApply();
        return $result ? true : false; 
    }

    public function storeApply($IdAccount, $CvFile, $CoverLetter, $IdOffer)
    {
        if (!isset($CvFile) || $CvFile['error'] !== UPLOAD_ERR_OK) {
            return "Aucun fichier téléchargé ou erreur lors du téléchargement.";
        }

        $cvTmpName = $CvFile['tmp_name'];
        $cvName = $CvFile['name'];
        $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($cvExt, $allowedExtensions)) {
            return "Le fichier doit être un PDF ou une image (JPG, JPEG, PNG, GIF).";
        }

        $cvDirectory = 'assets/cv/';
        if (!is_dir($cvDirectory) && !mkdir($cvDirectory, 0777, true)) {
            return "Erreur lors de la création du dossier de stockage.";
        }

        $cvNameUnique = uniqid('cv_', true) . '.' . $cvExt; 

        $cvPath = $cvDirectory . $cvNameUnique; 
        if (!move_uploaded_file($cvTmpName, $cvPath)) {
            return "Erreur lors du téléchargement du fichier.";
        }

        $dateApply = date('Y-m-d H:i:s');
        $store = $this->ApplyModel->StoreApply($IdAccount, $cvNameUnique, $CoverLetter, $dateApply, $IdOffer); 

        if (!$store) {
            return "Erreur lors de l'enregistrement de la candidature.";
        }

        return true;
    }

    public function editApply($IdApply, $newData)
    {
        $account = $this->ApplyModel->getApplyById($IdApply);
        if (!$account) {
            return false;
        }

        $result = $this->ApplyModel->editApply($IdApply, $newData);
        return $result ? true : false; 
    }

    public function storeApplication($id, $IdOffer, $coverLetter, $cvPath)
    {
        $this->ApplyModel->storeApplication($id, $IdOffer, $coverLetter, $cvPath);
    }
}