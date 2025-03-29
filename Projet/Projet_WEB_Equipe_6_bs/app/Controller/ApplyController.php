<?php

namespace app\Controller;

use app\Model\ApplyModel;


require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/ApplyModel.php';

class ApplyController
{
    private $ApplyModel;

    public function __construct($pdo)
    {
        $this->ApplyModel = new ApplyModel($pdo);
    }


    // Obtenir un compte par email
    public function getApply($IdApply)
    {
        $account = $this->ApplyModel->getApplyById($IdApply);
        return $account ? $account : "Apply introuvable!";
    }

    // Récupérer tous les comptes
    public function getAllApply()
    {
        $accounts = $this->ApplyModel->getAllApply();
        return $accounts ? $accounts : "Aucun Apply trouvé.";
    }

    // Supprimer un compte par ID
    public function removeApply($IdApply)
    {
        $account = $this->ApplyModel->getApplyById($IdApply);
        if (!$account) {
            return false; //"Apply introuvable!"
        }

        $result = $this->ApplyModel->removeApply($IdApply);
        return $result ? true : false; //"Apply supprimé avec succès!" : "Échec de la suppression du Apply."
    }

    // Supprimer tous les compte par ID
    public function removeAllApply($IdApply)
    {
        $account = $this->ApplyModel->getAllApply();
        if (!$account) {
            return false; //"Apply introuvable!"
        }

        $result = $this->ApplyModel->removeAllApply();
        return $result ? true : false; //"Apply supprimé avec succès!" : "Échec de la suppression du Apply."
    }

    public function storeApply($IdApply, $CvApply, $LetterApply, $DateApply)
    {
        $store = $this->ApplyModel->StoreApply($IdApply, $CvApply, $LetterApply, $DateApply);
        if (!$store) {
            return false; // Erreur de création
        }
        return true; // Création réussie
    }

    // Mettre à jour un compte
    public function editApply($IdApply, $newData)
    {
        $account = $this->ApplyModel->getApplyById($IdApply);
        if (!$account) {
            return false; //"Apply introuvable!"
        }

        $result = $this->ApplyModel->editApply($IdApply, $newData);
        return $result ? true : false; //"Apply mis à jour avec succès!" : "Échec de la mise à jour du Apply."
    }


}
?>