<?php

namespace app\Model;
use PDO;

require_once __DIR__.'/../../config/ConfigDatabase.php';

class Offer{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function GetOffer($id){
        $stmt = $this->pdo->prepare("SELECT * FROM offers where Id_Offer = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function GetAllOffer(){
        $stmt = $this -> pdo->prepare("SELECT * FROM offers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
    public function RemoveOffer($id){
        $stmt = $this -> pdo -> prepare("DELETE FROM offers WHERE Id_Offer = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function RemoveAllOffer(){
        $stmt = $this->pdo->prepare("DELETE FROM offers");
        return $stmt->execute();
    }

    public function EditOffer($id, $newdata){
        $stmt = $this->pdo->prepare("UPDATE offers SET Title_Offer = ?, Skills_Offer = ?, Address_Offer = ?, Date_Offer = ?, ActivitySector_Offer = ?, Salary_Offer = ?, Description_Offer = ? WHERE id_Offer = ?");
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