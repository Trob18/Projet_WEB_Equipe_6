<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/OfferModel.php';

/**
 * Contrôleur pour la gestion des offres d'emploi
 */
class OfferController {
    private $offerModel;

    /**
     * Constructeur du contrôleur des offres
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo) {
        $this->offerModel = new OfferModel($pdo);
    }

    /**
     * Récupère une offre spécifique par son identifiant
     * 
     * @param int $id Identifiant de l'offre
     * @return array|string Données de l'offre ou message d'erreur
     */
    public function GetOffer($id) {
        $offer = $this->offerModel->GetOffer($id);
        if (!$offer) {
            return "L'offre n'existe pas !";
        } else {
            return $offer;
        }
    }

    /**
     * Récupère toutes les offres
     * 
     * @return array|string Liste des offres ou message d'erreur
     */
    public function GetAllOffer() {
        $offers = $this->offerModel->GetAllOffer();
        if (!$offers) {
            return "Aucune Offre Trouvée !";
        } else {
            return $offers;
        }
    }

    /**
     * Crée une nouvelle offre
     * 
     * @param array $newdata Données de la nouvelle offre
     * @return string Message de succès ou d'erreur
     */
    public function createOffer($newdata) {
        $verif = ['TitleOffer', 'SkillsOffer', 'AddressOffer', 'DateOffer', 'ActivitySectorOffer', 'SalaryOffer', 'DescriptionOffer'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $offer = $this->offerModel->StoreOffer($newdata);
        if (!$offer) {
            return "Échec de la création";
        } else {
            return "Offre Créée !";
        }
    }

    /**
     * Supprime une offre spécifique
     * 
     * @param int $id Identifiant de l'offre à supprimer
     * @return string Message de succès ou d'erreur
     */
    public function RemoveOffer($id) {
        $offer = $this->offerModel->RemoveOffer($id);
        if (!$offer) {
            return "Offre Introuvable";
        } else {
            return "Offre Supprimée";
        }
    }

    /**
     * Supprime toutes les offres
     * 
     * @return string Message de succès ou d'erreur
     */
    public function RemoveAllOffer() {
        $offers = $this->offerModel->RemoveAllOffer();
        if (!$offers) {
            return "Offre(s) Introuvable(s)";
        } else {
            return "Toutes les offres sont supprimées";
        }
    }

    /**
     * Modifie une offre existante
     * 
     * @param int $id Identifiant de l'offre à modifier
     * @param array $newdata Nouvelles données de l'offre
     * @return string Message de succès ou d'erreur
     */
    public function EditOffer($id, $newdata) {
        $verif = ['TitleOffer', 'SkillsOffer', 'AddressOffer', 'DateOffer', 'ActivitySectorOffer', 'SalaryOffer', 'DescriptionOffer'];
        foreach ($verif as $index) {
            if (empty($newdata[$index])) {
                return "Contenu non complété !";
            }
        }
        $offer = $this->offerModel->EditOffer($id, $newdata);
        if (!$offer) {
            return "Échec de la Modification";
        } else {
            return "Modification Réussie !";
        }
    }
}
?>
