<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class OfferModel {
    private $pdo;

    /**
     * Constructeur de la classe OfferModel
     * 
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
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
        // Liste des colonnes valides pour éviter l'injection SQL
        $validColumns = [
            'Id_Offer', 'Title_Offer', 'Skills_Offer', 'Address_Offer', 
            'Date_Offer', 'ActivitySector_Offer', 'Salary_Offer', 'Description_Offer'
        ];

        if (!in_array($column, $validColumns) || (!in_array($selectColumn, $validColumns) && $selectColumn !== '*')) {
            return "Colonne invalide!";
        }

        // Sélectionner la colonne spécifique demandée
        $stmt = $this->pdo->prepare("SELECT $selectColumn FROM offers WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $selectColumn !== '*') {
            return $result[$selectColumn] ?? null;
        }

        return $result ?: null;
    }

    /**
     * Récupère toutes les offres
     * 
     * @return array Liste de toutes les offres
     */
    public function getAllOffers(){
        $stmt = $this->pdo->prepare("SELECT * FROM offers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle offre
     * 
     * @param array $newdata Données de la nouvelle offre
     * @return int|bool Identifiant de l'offre créée ou false en cas d'échec
     */
    public function storeOffer($newdata) {
        $stmt = $this->pdo->prepare("INSERT INTO offers (Title_Offer, Skills_Offer, Address_Offer, Date_Offer, ActivitySector_Offer, Salary_Offer, Description_Offer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $newdata['Title_Offer'],
            $newdata['Skills_Offer'],
            $newdata['Address_Offer'],
            $newdata['Date_Offer'],
            $newdata['ActivitySector_Offer'],
            $newdata['Salary_Offer'],
            $newdata['Description_Offer']
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Supprime une offre spécifique
     * 
     * @param int $id Identifiant de l'offre à supprimer
     * @return bool Résultat de l'opération
     */
    public function removeOffer($id){
        $stmt = $this->pdo->prepare("DELETE FROM offers WHERE Id_Offer = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Supprime toutes les offres
     * 
     * @return bool Résultat de l'opération
     */
    public function removeAllOffers(){
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
    public function editOffer($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE offers SET Title_Offer = ?, Skills_Offer = ?, Address_Offer = ?, Date_Offer = ?, ActivitySector_Offer = ?, Salary_Offer = ?, Description_Offer = ? WHERE Id_Offer = ?");
        return $stmt->execute([
            $newdata['Title_Offer'],
            $newdata['Skills_Offer'],
            $newdata['Address_Offer'],
            $newdata['Date_Offer'],
            $newdata['ActivitySector_Offer'],
            $newdata['Salary_Offer'],
            $newdata['Description_Offer'],
            $id
        ]);
    }
}
?>
