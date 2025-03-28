<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/OfferModel.php';

class OfferController{
    private $offerModel;

    public function __construct($pdo){
        $this->offerModel = new OfferModel($pdo);
    }

    public function GetOffer($id){
        $offer = $this->offerModel->GetOffer($id);
        if (!$offer){
            return "L'offre n'existe pas !";
        }
        else{
            return $offer;
        }
    }

    public function GetAllOffer(){
        $offers = $this->offerModel->GetAllOffer();
        if (!$offers){
            return "Aucune Offre Trouvé !";
        }
        else{
            return $offers;
        }
    }
    public function createOffer($newdata){
        $verif = ['TitleOffer', 'SkillsOffer', 'AddressOffer', 'DateOffer', 'ActivitySectorOffer', 'SalaryOffer', 'DescriptionOffer'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $offer = $this->offerModel->StoreOffer($newdata);
        if (!$offer){
            return "Echec de la creation";
        }
        else{
            return "Offre Créée ! ";
        }
    }

    public function RemoveOffer($id){
        $offer = $this->offerModel->RemoveOffer($id);
        if (!$offer){
            return "Offre Introuvable";
        }
        else{
            return "Offre Supprimée";
        }
    }

    public function RemoveAllOffer(){
        $offers = $this->offerModel->RemoveAllOffer();
        if (!$offers){
            return "Offre(s) Introuvable(s)";
        }
        else{
            return "Toutes les offres sont supprimées";
        }

    }

    public function EditOffer($id, $newdata){
        $verif = ['TitleOffer', 'SkillsOffer', 'AddressOffer', 'DateOffer', 'ActivitySectorOffer', 'SalaryOffer', 'DescriptionOffer'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $offer = $this->offerModel->EditOffer($id, $newdata);
        if (!$offer){
            return "Echec de la Modification";
        }
        else{
            return "Modification Reussie !";
        }
    }
}
?>