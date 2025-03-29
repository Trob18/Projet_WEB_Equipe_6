<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class Offer{
    private $pdo;

    /**
     * Constructeur de la classe Offer
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * Récupère une offre spécifique par son identifiant
     * 
     * @param int $id Identifiant de l'offre
     * @return array|false Données de l'offre ou false si non trouvée
     */
    public function GetOffer($id){
        $stmt = $this->pdo->prepare("SELECT * FROM offers WHERE Id_Offer = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les offres
     * 
     * @return array Liste de toutes les offres
     */
    public function GetAllOffer(){
        $stmt = $this->pdo->prepare("SELECT * FROM offers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle offre
     * 
     * @param array $newdata Données de la nouvelle offre
     * @return int Identifiant de l'offre créée
     */
    public function StoreOffer($newdata) {
        $stmt = $this->pdo->prepare("INSERT INTO offers (Title_Offer, Skills_Offer, Address_Offer, Date_Offer, ActivitySector_Offer, Salary_Offer, Description_Offer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $newdata['TitleOffer'],
            $newdata['SkillsOffer'],
            $newdata['AddressOffer'],
            $newdata['DateOffer'],
            $newdata['ActivitySectorOffer'],
            $newdata['SalaryOffer'],
            $newdata['DescriptionOffer']
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Supprime une offre spécifique
     * 
     * @param int $id Identifiant de l'offre à supprimer
     * @return bool Résultat de l'opération
     */
    public function RemoveOffer($id){
        $stmt = $this->pdo->prepare("DELETE FROM offers WHERE Id_Offer = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Supprime toutes les offres
     * 
     * @return bool Résultat de l'opération
     */
    public function RemoveAllOffer(){
        $stmt = $this->pdo->prepare("DELETE FROM offers");
        return $stmt->execute();
    }

    /**
     * Modifie une offre existante
     * 
     * @param int $id Identifiant de l'offre à modifier
     * @param array $newdata Nouvelles données de l'offre
     * @return bool Résultat de l'opération
     */
    public function EditOffer($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE offers SET Title_Offer = ?, Skills_Offer = ?, Address_Offer = ?, Date_Offer = ?, ActivitySector_Offer = ?, Salary_Offer = ?, Description_Offer = ? WHERE Id_Offer = ?");
        return $stmt->execute([
            $newdata['TitleOffer'],
            $newdata['SkillsOffer'],
            $newdata['AddressOffer'],
            $newdata['DateOffer'],
            $newdata['ActivitySectorOffer'],
            $newdata['SalaryOffer'],
            $newdata['DescriptionOffer'],
            $id
        ]);
    }
}
?>
