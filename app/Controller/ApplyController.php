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


    // Obtenir un compte par email
    public function getApply($column, $value, $selectColumn = '*')
    {
        $account = $this->ApplyModel->getApply($column, $value, $selectColumn);
        return $account ? $account : "Apply introuvable!";
    }

    // Récupérer tous les comptes
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

    public function storeApply($IdAccount, $CvFile, $CoverLetter, $IdOffer)
    {
        // Vérification si un fichier CV a été uploadé
        if (!isset($CvFile) || $CvFile['error'] !== UPLOAD_ERR_OK) {
            return "Aucun fichier téléchargé ou erreur lors du téléchargement.";
        }

        $cvTmpName = $CvFile['tmp_name'];
        $cvName = $CvFile['name'];
        $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

        // Vérification du type de fichier (PDF, JPG, PNG, JPEG, GIF)
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($cvExt, $allowedExtensions)) {
            return "Le fichier doit être un PDF ou une image (JPG, JPEG, PNG, GIF).";
        }

        // Création du dossier s'il n'existe pas
        $cvDirectory = 'assets/cv/';
        if (!is_dir($cvDirectory) && !mkdir($cvDirectory, 0777, true)) {
            return "Erreur lors de la création du dossier de stockage.";
        }

        // Générer un nom de fichier unique pour éviter les conflits
        $cvNameUnique = uniqid('cv_', true) . '.' . $cvExt; // Nom unique sans chemin complet

        // Déplacement du fichier vers le dossier final
        $cvPath = $cvDirectory . $cvNameUnique; // Le chemin complet pour le stockage sur le serveur
        if (!move_uploaded_file($cvTmpName, $cvPath)) {
            return "Erreur lors du téléchargement du fichier.";
        }

        // Enregistrement de la candidature en base de données
        $dateApply = date('Y-m-d H:i:s');
        $store = $this->ApplyModel->StoreApply($IdAccount, $cvNameUnique, $CoverLetter, $dateApply, $IdOffer); // Passer le nom du fichier unique

        if (!$store) {
            return "Erreur lors de l'enregistrement de la candidature.";
        }

        return true; // Retourne true en cas de succès
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

    public function storeApplication($id, $IdOffer, $coverLetter, $cvPath)
    {
        $this->ApplyModel->storeApplication($id, $IdOffer, $coverLetter, $cvPath);
    }
}
