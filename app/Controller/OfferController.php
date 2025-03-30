<?php

namespace app\Controller;

require_once __DIR__ . '/../../config/ConfigDatabase.php';
require_once __DIR__ . '/../Model/OfferModel.php';
use app\Model\OfferModel;
use PDO;

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
    public function __construct(PDO $pdo) {
        $this->offerModel = new OfferModel($pdo);
    }

    /**
     * Récupère une offre en fonction d'une colonne et d'une valeur spécifiques
     * 
     * @param string $column Nom de la colonne pour la condition
     * @param mixed $value Valeur à rechercher
     * @param string $selectColumn Colonne(s) à sélectionner (par défaut toutes '*')
     * @return mixed Résultat de la requête ou message d'erreur
     */
    public function getOffer($column, $value, $selectColumn = '*') {
        // Appel à la méthode du modèle
        $offer = $this->offerModel->getOffer($column, $value, $selectColumn);
        
        // Vérification du résultat et retour approprié
        return $offer ? $offer : "Offre introuvable!";
    }

    /**
     * Récupère toutes les offres
     * 
     * @return array|string Liste des offres ou message d'erreur
     */
    public function getAllOffers() {
        $offers = $this->offerModel->getAllOffers();
        return !empty($offers) ? $offers : "Aucune offre trouvée!";
    }

    /**
     * Crée une nouvelle offre
     * 
     * @param array $newdata Données de la nouvelle offre
     * @return string Message de succès ou d'erreur
     */
    public function createOffer($newdata) {
        $requiredFields = ['Title_Offer', 'Skills_Offer', 'Address_Offer', 'Date_Offer', 
                           'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer'];
        
        foreach ($requiredFields as $field) {
            if (empty($newdata[$field])) {
                return "Le champ '$field' est requis!";
            }
        }
        
        $result = $this->offerModel->storeOffer($newdata);
        return $result ? "Offre créée avec succès!" : "Échec de la création de l'offre.";
    }

    /**
     * Supprime une offre spécifique
     * 
     * @param int $id Identifiant de l'offre à supprimer
     * @return string Message de succès ou d'erreur
     */
    public function removeOffer($id) {
        // Vérifier si l'offre existe
        if (!$this->offerModel->getOffer('Id_Offer', $id)) {
            return "Offre introuvable!";
        }
        
        $result = $this->offerModel->removeOffer($id);
        return $result ? "Offre supprimée avec succès!" : "Échec de la suppression de l'offre.";
    }

    /**
     * Supprime toutes les offres
     * 
     * @return string Message de succès ou d'erreur
     */
    public function removeAllOffers() {
        $result = $this->offerModel->removeAllOffers();
        return $result ? "Toutes les offres ont été supprimées avec succès!" : "Échec de la suppression des offres.";
    }

    /**
     * Modifie une offre existante
     * 
     * @param int $id Identifiant de l'offre à modifier
     * @param array $newdata Nouvelles données de l'offre
     * @return string Message de succès ou d'erreur
     */
    public function editOffer($id, $newdata) {
        // Vérifier si l'offre existe
        if (!$this->offerModel->getOffer('Id_Offer', $id)) {
            return "Offre introuvable!";
        }
        
        $requiredFields = ['Title_Offer', 'Skills_Offer', 'Address_Offer', 'Date_Offer', 
                           'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer'];
        
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
