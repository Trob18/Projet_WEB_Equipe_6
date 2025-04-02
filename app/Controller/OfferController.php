<?php

namespace app\Controller;

require_once __DIR__ . '/../Model/OfferModel.php';
use app\Model\OfferModel;
use PDO;


class OfferController {
    private $offerModel;
    public function __construct(PDO $pdo) {
        $this->offerModel = new OfferModel($pdo);
    }

    public function getOffer($column, $value, $selectColumn = '*') {
        // Appel à la méthode du modèle
        $offer = $this->offerModel->getOffer($column, $value, $selectColumn);
        
        // Vérification du résultat et retour approprié
        return $offer ? $offer : "Offre introuvable!";
    }


    public function getAllOffers() {
        $offers = $this->offerModel->getAllOffers();
        return !empty($offers) ? $offers : "Aucune offre trouvée!";
    }

    public function createOffer($newdata) {
        $requiredFields = ['Title_Offer', 'Contract_Offer', 'Address_Offer',
                           'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer', 'Id_Company'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        $result = $this->offerModel->storeOffer($newdata);
        return $result ? "Offre créée avec succès!" : "Échec de la création de l'offre.";    
    }

    public function removeOffer($id) {
        // Vérifier si l'offre existe
        if (!$this->offerModel->getOffer('Id_Offer', $id)) {
            return "Offre introuvable!";
        }
        
        $result = $this->offerModel->removeOffer($id);
        return $result ? "Offre supprimée avec succès!" : "Échec de la suppression de l'offre.";
    }

    public function removeAllOffers() {
        $result = $this->offerModel->removeAllOffers();
        return $result ? "Toutes les offres ont été supprimées avec succès!" : "Échec de la suppression des offres.";
    }


    public function editOffer($id, $newdata) {
        // Vérifier si l'offre existe
        if (!$this->offerModel->getOffer('Id_Offer', $id)) {
            return "Offre introuvable!";
        }
        
        $requiredFields = ['Title_Offer', 'Contract_Offer', 'Address_Offer', 
                           'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer','Id_Company'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        
        $result = $this->offerModel->editOffer($id, $newdata);
        return $result ? "Offre modifiée avec succès!" : "Échec de la modification de l'offre.";
    }
    
}
?>